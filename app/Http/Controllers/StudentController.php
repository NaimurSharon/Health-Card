<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Classes;
use App\Models\Section;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['user', 'class', 'section', 'parent']);

        // Apply filters
        if ($request->has('shift') && $request->shift) {
            $query->whereHas('class', function($q) use ($request) {
                $q->where('shift', $request->shift);
            });
        }

        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->has('section_id') && $request->section_id) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->has('status') && $request->status) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $students = $query->orderBy('roll_number')->paginate(10);
        $classes = Classes::active()->get();
        $sections = Section::active()->get();

        return view('backend.students.index', compact('students', 'classes', 'sections'));
    }

    public function create()
    {
        $classes = Classes::active()->get();
        $sections = Section::active()->with('class')->get();
        $parents = User::where('role', 'parent')->where('status', 'active')->get();
        $schools = School::active()->get(); // Add this line
        $student = new Student();
    
        return view('backend.students.form', compact('classes', 'sections', 'parents', 'student', 'schools'));
    }
    
    public function edit(Student $student)
    {
        $classes = Classes::active()->get();
        $sections = Section::active()->with('class')->get();
        $parents = User::where('role', 'parent')->where('status', 'active')->get();
        $schools = School::active()->get(); // Add this line
    
        return view('backend.students.form', compact('student', 'classes', 'sections', 'parents', 'schools'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'student_id' => 'required|string|unique:students',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'roll_number' => 'required|integer',
            'blood_group' => 'nullable|string',
            'emergency_contact' => 'required|string',
            'school_id' => 'required|exists:schools,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'school_id' => $request->school_id,
        ]);

        Student::create([
            'user_id' => $user->id,
            'student_id' => $request->student_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'roll_number' => $request->roll_number,
            'parent_id' => $request->parent_id,
            'blood_group' => $request->blood_group,
            'allergies' => $request->allergies,
            'medical_conditions' => $request->medical_conditions,
            'emergency_contact' => $request->emergency_contact,
            'admission_date' => $request->admission_date ?? now(),
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        $student->load([
            'user', 'class', 'section', 'parent', 
            'medicalRecords', 'vaccinationRecords', 'healthCard',
            'diaryUpdates', 'appointments'
        ]);
        
        return view('backend.students.show', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->user_id,
            'student_id' => 'required|string|unique:students,student_id,' . $student->id,
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'roll_number' => 'required|integer',
            'blood_group' => 'nullable|string',
            'emergency_contact' => 'required|string',
            'school_id' => 'required|exists:schools,id',
        ]);

        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'school_id' => $request->school_id,
        ]);

        if ($request->password) {
            $student->user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $student->update([
            'student_id' => $request->student_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'roll_number' => $request->roll_number,
            'parent_id' => $request->parent_id,
            'blood_group' => $request->blood_group,
            'allergies' => $request->allergies,
            'medical_conditions' => $request->medical_conditions,
            'emergency_contact' => $request->emergency_contact,
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->user->delete();
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    public function generateIdCard(Student $student)
    {
        $student->load(['user', 'class', 'section', 'healthCard']);
        
        return view('backend.students.id-card', compact('student'));
    }

    public function bulkIdCards(Request $request)
    {
        $students = Student::with(['user', 'class', 'section', 'healthCard'])
            ->whereIn('id', $request->student_ids ?? [])
            ->get();
            
        return view('backend.students.bulk-id-cards', compact('students'));
    }
}