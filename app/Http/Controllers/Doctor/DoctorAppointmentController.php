<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\VideoConsultation;
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

        $consultations = VideoConsultation::with(['user', 'appointment'])
            ->forDoctor($doctorId);

        // Filter by status
        if ($status !== 'all') {
            $consultations->where('status', $status);
        }

        // Filter by date
        if ($date) {
            $consultations->whereDate('scheduled_for', $date);
        }

        $consultations = $consultations->orderBy('scheduled_for', 'desc')
            ->paginate(10);

        // Get upcoming consultations (scheduled and not yet started)
        $upcomingConsultations = VideoConsultation::with(['user'])
            ->forDoctor($doctorId)
            ->where('status', 'scheduled')
            ->where('scheduled_for', '>=', now())
            ->orderBy('scheduled_for')
            ->limit(5)
            ->get();

        $consultationStats = [
            'scheduled' => VideoConsultation::forDoctor($doctorId)->scheduled()->count(),
            'completed' => VideoConsultation::forDoctor($doctorId)->completed()->count(),
            'cancelled' => VideoConsultation::forDoctor($doctorId)->where('status', 'cancelled')->count(),
            'ongoing' => VideoConsultation::forDoctor($doctorId)->ongoing()->count(),
            'total' => VideoConsultation::forDoctor($doctorId)->count(),
        ];

        return view('doctor.appointments.index', compact(
            'consultations',
            'upcomingConsultations',
            'consultationStats',
            'status',
            'date'
        ));
    }

    public function show(VideoConsultation $consultation)
    {
        // Ensure doctor can only view their own consultations
        if ($consultation->doctor_id != auth()->id()) {
            abort(403);
        }

        $consultation->load(['user', 'appointment']);

        // Get patient's medical history if they're a student
        $medicalHistory = collect();
        if ($consultation->patient_type === 'student' && $consultation->user) {
            // Assuming there's a relationship or method to get student_id from user
            // You might need to adjust this based on your actual database structure
            $medicalHistory = MedicalRecord::with('recordedBy')
                ->whereHas('student', function ($query) use ($consultation) {
                    $query->where('user_id', $consultation->user_id);
                })
                ->orderBy('record_date', 'desc')
                ->limit(10)
                ->get();
        }

        return view('doctor.appointments.show', compact('consultation', 'medicalHistory'));
    }

    public function updateStatus(Request $request, VideoConsultation $consultation)
    {
        if ($consultation->doctor_id != auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled,ongoing,no_show',
            'doctor_notes' => 'nullable|string'
        ]);

        $consultation->update([
            'status' => $request->status,
            'doctor_notes' => $request->doctor_notes ?: $consultation->doctor_notes
        ]);

        // If completing the consultation, update ended_at
        if ($request->status === 'completed' && !$consultation->ended_at) {
            $consultation->update([
                'ended_at' => now(),
                'duration' => $consultation->calculateDuration()
            ]);
        }

        return redirect()->back()->with('success', 'Consultation status updated successfully.');
    }

    public function createMedicalRecord(VideoConsultation $consultation, Request $request)
    {
        if ($consultation->doctor_id != auth()->id()) {
            abort(403);
        }

        // Only create medical record for students
        if ($consultation->patient_type !== 'student') {
            return redirect()->back()->with('error', 'Medical records can only be created for students.');
        }

        $request->validate([
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'medication' => 'nullable|string',
            'doctor_notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date'
        ]);

        // Create medical record if patient is a student
        if ($consultation->patient_type === 'student') {
            // Get the student record
            $student = $consultation->student;

            if ($student) {
                MedicalRecord::create([
                    'user_id' => $student->user->id,
                    'record_date' => today(),
                    'record_type' => 'consultation',
                    'symptoms' => $consultation->symptoms,
                    'diagnosis' => $request->diagnosis,
                    'prescription' => $request->prescription,
                    'medication' => $request->medication,
                    'doctor_notes' => $request->doctor_notes,
                    'follow_up_date' => $request->follow_up_date,
                    'recorded_by' => auth()->id(),
                    'consultation_id' => $consultation->id
                ]);
            }
        }

        // Mark consultation as completed and add prescription/notes
        $consultation->update([
            'status' => 'completed',
            'prescription' => $request->prescription,
            'doctor_notes' => $request->doctor_notes,
            'ended_at' => now(),
            'duration' => $consultation->calculateDuration()
        ]);

        return redirect()->back()->with('success', 'Consultation completed and medical record created.');
    }
}