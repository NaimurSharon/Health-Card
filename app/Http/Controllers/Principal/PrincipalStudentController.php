<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Classes;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PrincipalStudentController extends Controller
{
    public function index()
    {
        $school = auth()->user()->school;
        $students = Student::with(['user', 'class', 'section'])
            ->where('school_id', $school->id)
            ->orderBy('class_id')
            ->orderBy('roll_number')
            ->paginate(15); 
            
        $classes = Classes::where('school_id', $school->id)->get();
        
        return view('principal.students.index', compact('students', 'classes'));
    }
    
    public function create()
    {
        $school = auth()->user()->school;
        $classes = Classes::where('school_id', $school->id)->get();
        $student = null;
        
        return view('principal.students.form', compact('classes','student'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'roll_number' => 'required|integer',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
        ]);
        
        $school = auth()->user()->school;
        
        // Create user account
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make('password123'),
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'role' => 'student',
            'school_id' => $school->id,
        ]);
        
        // Generate student ID
        $schoolCode = $school->code;
        $studentCount = Student::where('school_id', $school->id)->count() + 1;
        $studentId = $schoolCode . '-' . date('Y') . '-' . str_pad($studentCount, 3, '0', STR_PAD_LEFT);
        
        // Create student record
        Student::create([
            'user_id' => $user->id,
            'student_id' => $studentId,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'roll_number' => $request->roll_number,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'blood_group' => $request->blood_group,
            'allergies' => $request->allergies,
            'medical_conditions' => $request->medical_conditions,
            'emergency_contact' => $request->emergency_contact,
            'admission_date' => now(),
            'school_id' => $school->id,
        ]);
        
        return redirect()->route('principal.students.index')
            ->with('success', 'Student created successfully.');
    }
    
    public function show($id)
    {
        $student = Student::with(['user', 'class', 'section'])
            ->where('school_id', auth()->user()->school->id)
            ->findOrFail($id);
            
        return view('principal.students.show', compact('student'));
    }
    
    public function edit($id)
    {
        $school = auth()->user()->school;
        $student = Student::with(['user', 'class.sections'])
            ->where('school_id', $school->id)
            ->findOrFail($id);
            
        $classes = Classes::where('school_id', $school->id)->get();
        
        return view('principal.students.form', compact('student', 'classes'));
    }
    
    public function update(Request $request, $id)
    {
        $student = Student::where('school_id', auth()->user()->school->id)
            ->findOrFail($id);
            
            dd($request);
            
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'phone' => 'nullable|string|max:20',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'roll_number' => 'required|integer',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
        ]);
        
        // Update user
        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
        ]);
        
        // Update student
        $student->update([
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'roll_number' => $request->roll_number,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'blood_group' => $request->blood_group,
            'allergies' => $request->allergies,
            'medical_conditions' => $request->medical_conditions,
            'emergency_contact' => $request->emergency_contact,
        ]);
        
        return redirect()->route('principal.students.index')
            ->with('success', 'Student updated successfully.');
    }
    
    public function destroy($id)
    {
        $student = Student::where('school_id', auth()->user()->school->id)
            ->findOrFail($id);
            
        $student->user->delete();
        
        return redirect()->route('principal.students.index')
            ->with('success', 'Student deleted successfully.');
    }
    
    public function getSections($classId)
    {
        $sections = Section::where('class_id', $classId)->get();
        return response()->json($sections);
    }
}