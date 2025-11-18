<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DoctorAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $doctorId = auth()->id();
        $status = $request->get('status', 'all');
        $date = $request->get('date');

        $appointments = Appointment::with(['student.user', 'createdBy'])
            ->forDoctor($doctorId);

        // Filter by status
        if ($status !== 'all') {
            $appointments->where('status', $status);
        }

        // Filter by date
        if ($date) {
            $appointments->where('appointment_date', $date);
        }

        $appointments = $appointments->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time')
            ->paginate(10);

        $appointmentStats = [
            'scheduled' => Appointment::forDoctor($doctorId)->scheduled()->count(),
            'completed' => Appointment::forDoctor($doctorId)->completed()->count(),
            'cancelled' => Appointment::forDoctor($doctorId)->cancelled()->count(),
            'total' => Appointment::forDoctor($doctorId)->count(),
        ];

        return view('doctor.appointments.index', compact(
            'appointments',
            'appointmentStats',
            'status',
            'date'
        ));
    }

    public function show(Appointment $appointment)
    {
        // Ensure doctor can only view their own appointments
        if ($appointment->doctor_id !== auth()->id()) {
            abort(403);
        }

        $appointment->load(['student.user', 'doctor', 'createdBy']);

        // Get student's medical history
        $medicalHistory = MedicalRecord::with('recordedBy')
            ->forStudent($appointment->student_id)
            ->orderBy('record_date', 'desc')
            ->limit(10)
            ->get();

        return view('doctor.appointments.show', compact('appointment', 'medicalHistory'));
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

        MedicalRecord::create([
            'student_id' => $appointment->student_id,
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
}