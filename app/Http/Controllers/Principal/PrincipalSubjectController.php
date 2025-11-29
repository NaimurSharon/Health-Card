<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Classes;
use App\Models\Section;
use App\Models\User;
use App\Models\ClassSubject;
use Illuminate\Http\Request;

class PrincipalSubjectController extends Controller
{
    public function index()
    {
        $school = auth()->user()->school;
        $subjects = Subject::where('school_id', $school->id)
            ->orderBy('name')
            ->paginate(15);
            
        return view('principal.subjects.index', compact('subjects'));
    }
    
    public function create()
    {
        return view('principal.subjects.form');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code',
            'type' => 'required|in:compulsory,optional,extra-curricular',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        
        $school = auth()->user()->school;
        
        Subject::create([
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'description' => $request->description,
            'status' => $request->status,
            'school_id' => $school->id,
        ]);
        
        return redirect()->route('principal.subjects.index')
            ->with('success', 'Subject created successfully.');
    }
    
    public function edit($id)
    {
        $school = auth()->user()->school;
        $subject = Subject::where('school_id', $school->id)
            ->findOrFail($id);
            
        return view('principal.subjects.form', compact('subject'));
    }
    
    public function update(Request $request, $id)
    {
        $school = auth()->user()->school;
        $subject = Subject::where('school_id', $school->id)
            ->findOrFail($id);
            
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $id,
            'type' => 'required|in:compulsory,optional,extra-curricular',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        
        $subject->update($request->all());
        
        return redirect()->route('principal.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }
    
    public function destroy($id)
    {
        $school = auth()->user()->school;
        $subject = Subject::where('school_id', $school->id)
            ->findOrFail($id);
            
        // Check if subject is assigned to any class
        if ($subject->classSubjects()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete subject that is assigned to classes. Please remove assignments first.');
        }
        
        $subject->delete();
        
        return redirect()->route('principal.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }

    public function assignTeachers()
    {
        $school = auth()->user()->school;
        $classes = Classes::with('sections')->where('school_id', $school->id)->get();
        $subjects = Subject::where('school_id', $school->id)->get();
        $teachers = User::where('school_id', $school->id)
            ->where('role', 'teacher')
            ->where('status', 'active')
            ->get();
        $assignments = ClassSubject::with(['class', 'section', 'subject', 'teacher'])
            ->whereHas('class', function($query) use ($school) {
                $query->where('school_id', $school->id);
            })
            ->orderBy('class_id')
            ->orderBy('section_id')
            ->get();
            
        return view('principal.subjects.assign-teachers', compact('classes', 'subjects', 'teachers', 'assignments'));
    }
    
    public function storeAssignTeachers(Request $request)
    {
        $school = auth()->user()->school;
        
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
        ]);

        // Verify that the class, section, subject, and teacher belong to the same school
        $class = Classes::where('id', $request->class_id)->where('school_id', $school->id)->first();
        $section = Section::where('id', $request->section_id)->where('school_id', $school->id)->first();
        $subject = Subject::where('id', $request->subject_id)->where('school_id', $school->id)->first();
        $teacher = User::where('id', $request->teacher_id)->where('school_id', $school->id)->first();

        if (!$class || !$section || !$subject || !$teacher) {
            return redirect()->back()
                ->with('error', 'Invalid selection. Please ensure all selected items belong to your school.');
        }
        
        // Check if assignment already exists
        $existing = ClassSubject::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('subject_id', $request->subject_id)
            ->first();
            
        if ($existing) {
            return redirect()->back()
                ->with('error', 'This subject is already assigned to this class section.');
        }
        
        // Create the assignment with school_id
        ClassSubject::create([
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'school_id' => $school->id,
        ]);
        
        return redirect()->route('principal.subjects.assign-teachers')
            ->with('success', 'Teacher assigned successfully.');
    }

    public function destroyAssignment($id)
    {
        $school = auth()->user()->school;
        $assignment = ClassSubject::where('school_id', $school->id)->findOrFail($id);
        $assignment->delete();

        return redirect()->route('principal.subjects.assign-teachers')
            ->with('success', 'Assignment removed successfully.');
    }

    // AJAX method to get sections for a class
    public function getSections($classId)
    {
        $school = auth()->user()->school;
        $sections = Section::where('class_id', $classId)
            ->where('school_id', $school->id)
            ->get();
        return response()->json($sections);
    }
}