<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $doctorId = auth()->id();
        $status = $request->get('status', 'all');
        $date = $request->get('date');
        $patientType = $request->get('patient_type');

        // Updated to use 'user' relationship instead of 'student.user'
        $appointments = Appointment::with(['user', 'student', 'doctor', 'createdBy'])
            ->forDoctor($doctorId);

        // Filter by status
        if ($status !== 'all') {
            $appointments->where('status', $status);
        }

        // Filter by date
        if ($date) {
            $appointments->where('appointment_date', $date);
        }

        // Filter by patient type
        if ($patientType && $patientType !== 'all') {
            $appointments->byPatientType($patientType);
        }

        $appointments = $appointments->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time')
            ->paginate(10);

        $appointmentStats = [
            'scheduled' => Appointment::forDoctor($doctorId)->scheduled()->count(),
            'completed' => Appointment::forDoctor($doctorId)->completed()->count(),
            'cancelled' => Appointment::forDoctor($doctorId)->cancelled()->count(),
            'no_show' => Appointment::forDoctor($doctorId)->noShow()->count(),
            'total' => Appointment::forDoctor($doctorId)->count(),
        ];

        // Stats by patient type
        $patientTypeStats = [
            'student' => Appointment::forDoctor($doctorId)->forStudents()->count(),
            'teacher' => Appointment::forDoctor($doctorId)->forTeachers()->count(),
            'public' => Appointment::forDoctor($doctorId)->forPublic()->count(),
        ];

        return view('doctor.appointments.index', compact(
            'appointments',
            'appointmentStats',
            'patientTypeStats',
            'status',
            'date',
            'patientType'
        ));
    }

    public function show(Appointment $appointment)
    {
        // Ensure doctor can only view their own appointments
        if ($appointment->doctor_id !== auth()->id()) {
            abort(403);
        }

        // Load relationships - use 'user' instead of 'student.user'
        $appointment->load(['user', 'student', 'doctor', 'createdBy']);

        // Get patient's medical history
        // Updated to use user_id instead of student_id
        $medicalHistory = MedicalRecord::with('recordedBy')
            ->where('user_id', $appointment->user_id)
            ->orderBy('record_date', 'desc')
            ->limit(10)
            ->get();

        // Get patient details based on type
        $patientDetails = $appointment->getPatientDetails();

        return view('doctor.appointments.show', compact(
            'appointment', 
            'medicalHistory',
            'patientDetails'
        ));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        if ($appointment->doctor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled,no_show',
            'notes' => 'nullable|string'
        ]);

        $appointment->update([
            'status' => $request->status,
            'notes' => $request->notes ?: $appointment->notes
        ]);

        return redirect()->back()->with('success', 'Appointment status updated successfully.');
    }

    public function createMedicalRecord(Appointment $appointment, Request $request)
    {
        if ($appointment->doctor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'medication' => 'nullable|string',
            'doctor_notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date'
        ]);

        // Updated to use user_id instead of student_id
        MedicalRecord::create([
            'user_id' => $appointment->user_id,
            'patient_type' => $appointment->patient_type,
            'record_date' => today(),
            'record_type' => 'checkup',
            'symptoms' => $appointment->symptoms,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'medication' => $request->medication,
            'doctor_notes' => $request->doctor_notes,
            'follow_up_date' => $request->follow_up_date,
            'recorded_by' => auth()->id()
        ]);

        // Mark appointment as completed
        $appointment->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Medical record created and appointment completed.');
    }

    /**
     * Cancel an appointment
     */
    public function cancel(Appointment $appointment, Request $request)
    {
        // Check if user can cancel (either patient or doctor)
        if (!$appointment->canAccess(auth()->id())) {
            abort(403);
        }

        if (!$appointment->canBeCancelled()) {
            return redirect()->back()->with('error', 'This appointment cannot be cancelled. Cancellations must be made at least 2 hours before the appointment time.');
        }

        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500'
        ]);

        $appointment->update([
            'status' => 'cancelled',
            'notes' => $request->cancellation_reason 
                ? ($appointment->notes ? $appointment->notes . "\n\nCancellation Reason: " . $request->cancellation_reason : "Cancellation Reason: " . $request->cancellation_reason)
                : $appointment->notes
        ]);

        return redirect()->back()->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Reschedule an appointment
     */
    public function reschedule(Appointment $appointment, Request $request)
    {
        // Check if user can reschedule
        if (!$appointment->canAccess(auth()->id())) {
            abort(403);
        }

        if (!$appointment->canBeRescheduled()) {
            return redirect()->back()->with('error', 'This appointment cannot be rescheduled.');
        }

        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'reschedule_reason' => 'nullable|string|max:500'
        ]);

        $oldDateTime = $appointment->appointment_date->format('Y-m-d') . ' ' . $appointment->appointment_time;
        
        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'notes' => $request->reschedule_reason
                ? ($appointment->notes ? $appointment->notes . "\n\nRescheduled from {$oldDateTime}. Reason: " . $request->reschedule_reason : "Rescheduled from {$oldDateTime}. Reason: " . $request->reschedule_reason)
                : ($appointment->notes ? $appointment->notes . "\n\nRescheduled from {$oldDateTime}" : "Rescheduled from {$oldDateTime}")
        ]);

        return redirect()->back()->with('success', 'Appointment rescheduled successfully.');
    }

    /**
     * Get upcoming appointments for a user
     */
    public function upcoming()
    {
        $userId = auth()->id();
        
        $appointments = Appointment::with(['doctor', 'user'])
            ->forUser($userId)
            ->scheduled()
            ->where(function($query) {
                $query->where('appointment_date', '>', today())
                    ->orWhere(function($q) {
                        $q->where('appointment_date', today())
                          ->where('appointment_time', '>=', now()->format('H:i:s'));
                    });
            })
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        return view('appointments.upcoming', compact('appointments'));
    }

    /**
     * Get past appointments for a user
     */
    public function past()
    {
        $userId = auth()->id();
        
        $appointments = Appointment::with(['doctor', 'user'])
            ->forUser($userId)
            ->past()
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(10);

        return view('appointments.past', compact('appointments'));
    }
}