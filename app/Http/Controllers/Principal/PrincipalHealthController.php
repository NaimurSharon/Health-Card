<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\AnnualHealthRecord;
use App\Models\StudentHealthReport;
use Illuminate\Http\Request;

class PrincipalHealthController extends Controller
{
    public function index()
    {
        $school = auth()->user()->school;
        $students = Student::with(['user', 'class', 'annualHealthRecords'])
            ->where('school_id', $school->id)
            ->whereHas('annualHealthRecords')
            ->orderBy('class_id')
            ->orderBy('roll_number')
            ->get();
            
        return view('principal.health.records', compact('students'));
    }
    
    public function studentRecords($studentId)
    {
        $student = Student::with(['user', 'class', 'annualHealthRecords'])
            ->where('school_id', auth()->user()->school->id)
            ->findOrFail($studentId);
            
        $healthRecords = AnnualHealthRecord::where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('principal.health.student-records', compact('student', 'healthRecords'));
    }
    
    public function annualRecords()
    {
        $school = auth()->user()->school;
        $records = AnnualHealthRecord::with(['student.user', 'student.class'])
            ->where('school_id', $school->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('principal.health.annual-records', compact('records'));
    }
    
    public function createAnnualRecord()
    {
        $school = auth()->user()->school;
        $students = Student::with('user')
            ->where('school_id', $school->id)
            ->orderBy('class_id')
            ->orderBy('roll_number')
            ->get();
            
        return view('principal.health.create-annual-record', compact('students'));
    }
    
    public function storeAnnualRecord(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'age' => 'required|integer|min:3|max:18',
            'weight' => 'required|numeric|min:10|max:100',
            'height' => 'required|numeric|min:50|max:200',
            'head_circumference' => 'nullable|numeric|min:30|max:60',
            'development_notes' => 'nullable|string',
            'difficulties' => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'general_health' => 'required|string',
            'vaccination_status' => 'required|string',
            'nutrition_notes' => 'nullable|string',
        ]);
        
        $school = auth()->user()->school;
        
        AnnualHealthRecord::create([
            'student_id' => $request->student_id,
            'school_id' => $school->id,
            'age' => $request->age,
            'weight' => $request->weight,
            'height' => $request->height,
            'head_circumference' => $request->head_circumference,
            'development_notes' => $request->development_notes,
            'difficulties' => $request->difficulties,
            'special_instructions' => $request->special_instructions,
            'general_health' => $request->general_health,
            'vaccination_status' => $request->vaccination_status,
            'nutrition_notes' => $request->nutrition_notes,
            'recorded_by' => auth()->id(),
        ]);
        
        return redirect()->route('principal.health.annual-records')
            ->with('success', 'Health record created successfully.');
    }
    
    public function editAnnualRecord($id)
    {
        $record = AnnualHealthRecord::where('school_id', auth()->user()->school->id)
            ->findOrFail($id);
            
        return view('principal.health.edit-annual-record', compact('record'));
    }
    
    public function updateAnnualRecord(Request $request, $id)
    {
        $record = AnnualHealthRecord::where('school_id', auth()->user()->school->id)
            ->findOrFail($id);
            
        $request->validate([
            'age' => 'required|integer|min:3|max:18',
            'weight' => 'required|numeric|min:10|max:100',
            'height' => 'required|numeric|min:50|max:200',
            'head_circumference' => 'nullable|numeric|min:30|max:60',
            'development_notes' => 'nullable|string',
            'difficulties' => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'general_health' => 'required|string',
            'vaccination_status' => 'required|string',
            'nutrition_notes' => 'nullable|string',
        ]);
        
        $record->update($request->all());
        
        return redirect()->route('principal.health.annual-records')
            ->with('success', 'Health record updated successfully.');
    }
}