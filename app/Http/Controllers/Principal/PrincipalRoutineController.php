<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Routine;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use App\Models\ClassSubject;
use Illuminate\Http\Request;

class PrincipalRoutineController extends Controller
{
    public function index()
    {
        $school = auth()->user()->school;
        $classes = Classes::where('school_id', $school->id)->get();
        
        $selectedClass = request('class_id');
        $selectedSection = request('section_id');
        $routines = collect();
        
        if ($selectedClass) {
            $routines = Routine::with(['class', 'section', 'subject', 'teacher'])
                ->where('class_id', $selectedClass)
                ->when($selectedSection, function($query) use ($selectedSection) {
                    return $query->where('section_id', $selectedSection);
                })
                ->whereHas('class', function($query) use ($school) {
                    $query->where('school_id', $school->id);
                })
                ->orderBy('day_of_week')
                ->orderBy('period')
                ->get()
                ->groupBy('day_of_week');
        }
        
        return view('principal.routine.index', compact('routines', 'classes', 'selectedClass', 'selectedSection'));
    }
    
    public function weekly()
    {
        $school = auth()->user()->school;
        $classes = Classes::where('school_id', $school->id)->get();
        
        $selectedClass = request('class_id');
        $selectedSection = request('section_id');
        $routines = collect();
        
        if ($selectedClass) {
            $routines = Routine::with(['section', 'subject', 'teacher'])
                ->where('class_id', $selectedClass)
                ->when($selectedSection, function($query) use ($selectedSection) {
                    return $query->where('section_id', $selectedSection);
                })
                ->whereHas('class', function($query) use ($school) {
                    $query->where('school_id', $school->id);
                })
                ->orderBy('day_of_week')
                ->orderBy('period')
                ->get()
                ->groupBy(['day_of_week', 'section_id']);
        }
        
        return view('principal.routine.weekly', compact('classes', 'routines', 'selectedClass', 'selectedSection'));
    }

    
    public function create()
    {
        $school = auth()->user()->school;
        $classes = Classes::where('school_id', $school->id)->get();
        $subjects = Subject::where('school_id', $school->id)->get();
        $teachers = User::where('school_id', $school->id)
            ->where('role', 'teacher')
            ->where('status', 'active')
            ->get();
        
        return view('principal.routine.form', compact('classes', 'subjects', 'teachers'));
    }
    
    public function store(Request $request)
    {
        $school = auth()->user()->school;
        
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day_of_week' => 'required|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'period' => 'required|integer|min:1|max:10',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:50',
            'academic_year' => 'required|integer|min:2000|max:2030',
        ]);
    
        // Verify all related records belong to the school
        $class = Classes::where('id', $request->class_id)->where('school_id', $school->id)->first();
        $section = Section::where('id', $request->section_id)->where('school_id', $school->id)->first();
        $subject = Subject::where('id', $request->subject_id)->where('school_id', $school->id)->first();
        $teacher = User::where('id', $request->teacher_id)->where('school_id', $school->id)->first();
    
        if (!$class || !$section || !$subject || !$teacher) {
            return redirect()->back()
                ->with('error', 'Invalid selection. Please ensure all selected items belong to your school.');
        }
    
        // Check for time conflict
        $conflict = Routine::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('day_of_week', $request->day_of_week)
            ->where('period', $request->period)
            ->first();
            
        if ($conflict) {
            return redirect()->back()
                ->with('error', 'Time slot conflict detected. Please choose a different period or time.');
        }
    
        Routine::create([
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'day_of_week' => $request->day_of_week,
            'period' => $request->period,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'room' => $request->room,
            'academic_year' => $request->academic_year,
            'school_id' => $school->id,
        ]);
    
        return redirect()->route('principal.routine.index')
            ->with('success', 'Routine created successfully.');
    }
    
    public function edit($id)
    {
        $school = auth()->user()->school;
        
        $routine = Routine::with(['class', 'section', 'subject', 'teacher'])
            ->where('school_id', $school->id)
            ->findOrFail($id);
        
        $classes = Classes::where('school_id', $school->id)->get();
        $subjects = Subject::where('school_id', $school->id)->get();
        $teachers = User::where('school_id', $school->id)
            ->where('role', 'teacher')
            ->where('status', 'active')
            ->get();
        
        return view('principal.routine.form', compact('routine', 'classes', 'subjects', 'teachers'));
    }
    
    public function update(Request $request, $id)
    {
        $school = auth()->user()->school;
        $routine = Routine::where('school_id', $school->id)->findOrFail($id);
        
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day_of_week' => 'required|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'period' => 'required|integer|min:1|max:10',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:50',
            'academic_year' => 'required|integer|min:2000|max:2030',
        ]);
    
        // Verify all related records belong to the school
        $class = Classes::where('id', $request->class_id)->where('school_id', $school->id)->first();
        $section = Section::where('id', $request->section_id)->where('school_id', $school->id)->first();
        $subject = Subject::where('id', $request->subject_id)->where('school_id', $school->id)->first();
        $teacher = User::where('id', $request->teacher_id)->where('school_id', $school->id)->first();
    
        if (!$class || !$section || !$subject || !$teacher) {
            return redirect()->back()
                ->with('error', 'Invalid selection. Please ensure all selected items belong to your school.');
        }
    
        // Check for time conflict excluding current routine
        $conflict = Routine::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('day_of_week', $request->day_of_week)
            ->where('period', $request->period)
            ->where('id', '!=', $id)
            ->first();
            
        if ($conflict) {
            return redirect()->back()
                ->with('error', 'Time slot conflict detected. Please choose a different period or time.');
        }
    
        $routine->update([
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'day_of_week' => $request->day_of_week,
            'period' => $request->period,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'room' => $request->room,
            'academic_year' => $request->academic_year,
        ]);
    
        return redirect()->route('principal.routine.index')
            ->with('success', 'Routine updated successfully.');
    }
    
    public function getSections($classId)
    {
        try {
            $school = auth()->user()->school;
            $sections = Section::where('class_id', $classId)
                ->where('school_id', $school->id)
                ->where('status', 'active')
                ->get(['id', 'name']);
            
            return response()->json($sections);
        } catch (\Exception $e) {
            return response()->json([], 500);
        }
    }

    public function getClassSubjects($classId)
    {
        try {
            $school = auth()->user()->school;
            
            // Get subjects that are assigned to this class through class_subjects table
            $subjects = Subject::whereHas('classSubjects', function($query) use ($classId, $school) {
                    $query->where('class_id', $classId)
                          ->where('school_id', $school->id);
                })
                ->orWhere(function($query) use ($school) {
                    // Or get all active subjects for the school as fallback
                    $query->where('school_id', $school->id)
                          ->where('status', 'active');
                })
                ->get(['id', 'name', 'code']);
            
            return response()->json($subjects);
        } catch (\Exception $e) {
            \Log::error('Error loading class subjects: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    public function getTeachers($subjectId)
    {
        try {
            $school = auth()->user()->school;
            
            // Get teachers who are assigned to this subject through class_subjects table
            $teachers = User::whereHas('classSubjects', function($query) use ($subjectId, $school) {
                    $query->where('subject_id', $subjectId)
                          ->where('school_id', $school->id);
                })
                ->orWhere(function($query) use ($school) {
                    // Or get all active teachers for the school as fallback
                    $query->where('school_id', $school->id)
                          ->where('role', 'teacher')
                          ->where('status', 'active');
                })
                ->get(['id', 'name']);
            
            return response()->json($teachers);
        } catch (\Exception $e) {
            \Log::error('Error loading teachers: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }
    
    public function destroy($id)
    {
        $routine = Routine::findOrFail($id);
        $routine->delete();
        
        return redirect()->route('principal.routine.index')
            ->with('success', 'Routine deleted successfully.');
    }
}