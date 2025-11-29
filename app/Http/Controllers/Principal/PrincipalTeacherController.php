<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use App\Models\Section;
use App\Models\ClassSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PrincipalTeacherController extends Controller
{
    public function index()
    {
        $school = auth()->user()->school;
        $teachers = User::with(['classes', 'subjects'])
        ->where('school_id', $school->id)
        ->where('role', 'teacher')
        ->orderBy('name')
        ->paginate(15);

            
        return view('principal.teachers.index', compact('teachers'));
    }
    
    public function create()
    {
        $teacher = null;   // must send this
        return view('principal.teachers.form', compact('teacher'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'qualifications' => 'required|string',
            'specialization' => 'required|string',
        ]);
        
        $school = auth()->user()->school;
        $teacher = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make('password123'),
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'qualifications' => $request->qualifications,
            'specialization' => $request->specialization,
            'role' => 'teacher',
            'school_id' => $school->id,
        ]);
        
        return redirect()->route('principal.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }
    
    public function show($id)
    {
        $teacher = User::with(['assignedClasses.class', 'assignedClasses.section', 'assignedSubjects'])
            ->where('school_id', auth()->user()->school->id)
            ->where('role', 'teacher')
            ->findOrFail($id);
            
        return view('principal.teachers.show', compact('teacher'));
    }
    
    public function edit($id)
    {
        $teacher = User::where('school_id', auth()->user()->school->id)
            ->where('role', 'teacher')
            ->findOrFail($id);
            
        return view('principal.teachers.form', compact('teacher'));
    }
    
    public function update(Request $request, $id)
    {
        $teacher = User::where('school_id', auth()->user()->school->id)
            ->where('role', 'teacher')
            ->findOrFail($id);
            
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'qualifications' => 'required|string',
            'specialization' => 'required|string',
        ]);
        
        $teacher->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'qualifications' => $request->qualifications,
            'specialization' => $request->specialization,
        ]);
        
        return redirect()->route('principal.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }
    
    public function destroy($id)
    {
        $teacher = User::where('school_id', auth()->user()->school->id)
            ->where('role', 'teacher')
            ->findOrFail($id);
            
        $teacher->delete();
        
        return redirect()->route('principal.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
    
    public function assignClasses($id)
    {
        $teacher = User::where('school_id', auth()->user()->school->id)
            ->where('role', 'teacher')
            ->findOrFail($id);
            
        $classes = Classes::with('sections')->where('school_id', auth()->user()->school->id)->get();
        $assignedClasses = ClassSubject::where('teacher_id', $id)->get();
        
        return view('principal.teachers.assign-classes', compact('teacher', 'classes', 'assignedClasses'));
    }
    
    public function storeAssignClasses(Request $request, $id)
    {
        $request->validate([
            'class_subjects' => 'required|array',
            'class_subjects.*.class_id' => 'required|exists:classes,id',
            'class_subjects.*.section_id' => 'required|exists:sections,id',
            'class_subjects.*.subject_id' => 'required|exists:subjects,id',
        ]);
        
        // Remove existing assignments
        ClassSubject::where('teacher_id', $id)->delete();
        
        // Add new assignments
        foreach ($request->class_subjects as $assignment) {
            ClassSubject::create([
                'class_id' => $assignment['class_id'],
                'section_id' => $assignment['section_id'],
                'subject_id' => $assignment['subject_id'],
                'teacher_id' => $id,
            ]);
        }
        
        return redirect()->route('principal.teachers.show', $id)
            ->with('success', 'Classes assigned successfully.');
    }
}