<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\MedicalRecord;
use App\Models\VideoConsultation;
use App\Models\Appointment;
use App\Models\VaccinationRecord;
use Illuminate\Http\Request;

class DoctorPatientController extends Controller
{
    public function index(Request $request)
    {
        $doctorId = auth()->id();
        $search = $request->get('search');

        $students = Student::with([
            'user',
            'class',
            'medicalRecords' => function ($query) use ($doctorId) {
                $query->recordedByDoctor($doctorId);
            }
        ])
            ->whereHas('medicalRecords', function ($query) use ($doctorId) {
                $query->recordedByDoctor($doctorId);
            });

        if ($search) {
            $students->where(function ($query) use ($search) {
                $query->where('student_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $students = $students->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('doctor.patients.index', compact('students', 'search'));
    }

    public function show(Student $student)
    {
        $doctorId = auth()->id();

        $student->load(['user', 'class', 'healthCard']);

        // Medical records by this doctor
        $medicalRecords = MedicalRecord::with('recordedBy')
            ->forUser($student->user->id)
            ->recordedByDoctor($doctorId)
            ->orderBy('record_date', 'desc')
            ->paginate(10);

        $upcomingConsultations = VideoConsultation::with('doctor', 'user')
            ->forDoctor($doctorId)
            ->forUser($student->user->id)
            ->forStudents()
            ->scheduled()
            ->where('scheduled_for', '>=', now())
            ->orderBy('scheduled_for')
            ->get();

        // Statistics
        $stats = [
            'total_visits' => $medicalRecords->total(),
            'last_visit' => $medicalRecords->first()?->record_date,
            'upcoming_consultations' => $upcomingConsultations->count(),
        ];

        return view('doctor.patients.show', compact(
            'student',
            'medicalRecords',
            'upcomingConsultations',
            'stats'
        ));
    }


    public function createMedicalRecord(Student $student, Request $request)
    {
        $request->validate([
            'record_type' => 'required|in:checkup,vaccination,emergency,routine,sickness',
            'symptoms' => 'required|string',
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'medication' => 'nullable|string',
            'doctor_notes' => 'nullable|string',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'temperature' => 'nullable|numeric',
            'blood_pressure' => 'nullable|string',
            'follow_up_date' => 'nullable|date'
        ]);

        MedicalRecord::create([
            'user_id' => $student->user->id,
            'record_date' => today(),
            'record_type' => $request->record_type,
            'symptoms' => $request->symptoms,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'medication' => $request->medication,
            'doctor_notes' => $request->doctor_notes,
            'height' => $request->height,
            'weight' => $request->weight,
            'temperature' => $request->temperature,
            'blood_pressure' => $request->blood_pressure,
            'follow_up_date' => $request->follow_up_date,
            'recorded_by' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Medical record created successfully.');
    }
}