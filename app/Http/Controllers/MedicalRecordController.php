<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Student;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $doctorId = auth()->id();
        $search = $request->get('search');
        $recordType = $request->get('record_type');

        $medicalRecords = MedicalRecord::with(['student.user', 'recordedBy'])
            ->recordedByDoctor($doctorId)
            ->latest('record_date');

        if ($search) {
            $medicalRecords->where(function ($query) use ($search) {
                $query->where('diagnosis', 'like', "%{$search}%")
                    ->orWhere('symptoms', 'like', "%{$search}%")
                    ->orWhereHas('student.user', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($recordType) {
            $medicalRecords->where('record_type', $recordType);
        }

        $medicalRecords = $medicalRecords->paginate(15);

        $recordTypes = [
            'checkup' => 'Regular Checkup',
            'vaccination' => 'Vaccination',
            'emergency' => 'Emergency',
            'routine' => 'Routine Visit',
            'sickness' => 'Sickness'
        ];

        return view('doctor.medical-records.index', compact(
            'medicalRecords',
            'search',
            'recordType',
            'recordTypes'
        ));
    }

    public function create()
    {
        $students = Student::with('user')->active()->get();
        return view('doctor.medical-records.form', [
            'students' => $students
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'record_date' => 'required|date',
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

        // Get student and convert to user_id
        $student = Student::findOrFail($request->student_id);

        MedicalRecord::create([
            'user_id' => $student->user_id,
            'patient_type' => 'student',
            'record_date' => $request->record_date,
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

        return redirect()->route('doctor.medical-records.index')
            ->with('success', 'Medical record created successfully.');
    }

    public function show(MedicalRecord $medicalRecord)
    {
        if ($medicalRecord->recorded_by != auth()->id()) {
            abort(403, 'This patient record was created by another doctor and cannot be accessed.');
        }


        $medicalRecord->load(['student.user', 'recordedBy']);
        return view('doctor.medical-records.show', compact('medicalRecord'));
    }

    public function edit(MedicalRecord $medicalRecord)
    {
        if ($medicalRecord->recorded_by !== auth()->id()) {
            abort(403);
        }

        $students = Student::with('user')->active()->get();
        return view('doctor.medical-records.form', [
            'medicalRecord' => $medicalRecord,
            'students' => $students
        ]);
    }

    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        if ($medicalRecord->recorded_by !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'record_date' => 'required|date',
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

        $medicalRecord->update($request->all());

        return redirect()->route('doctor.medical-records.index')
            ->with('success', 'Medical record updated successfully.');
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        if ($medicalRecord->recorded_by !== auth()->id()) {
            abort(403);
        }

        $medicalRecord->delete();

        return redirect()->route('doctor.medical-records.index')
            ->with('success', 'Medical record deleted successfully.');
    }
}