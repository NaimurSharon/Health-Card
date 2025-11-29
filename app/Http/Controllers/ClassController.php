<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Routine;
use App\Models\ClassSubject;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of the classes.
     */
    public function index(Request $request)
    {
        $query = Classes::withCount(['students', 'sections']);

        // Apply filters
        if ($request->has('shift') && $request->shift) {
            $query->where('shift', $request->shift);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $classes = $query->orderBy('numeric_value')->paginate(10);

        return view('backend.classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new class.
     */
    public function create()
    {
        return view('backend.classes.form');
    }

    /**
     * Store a newly created class in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:classes,name',
            'numeric_value' => 'required|integer|min:1|max:12|unique:classes,numeric_value',
            'shift' => 'required|in:morning,day',
            'capacity' => 'required|integer|min:1|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        Classes::create([
            'name' => $request->name,
            'numeric_value' => $request->numeric_value,
            'shift' => $request->shift,
            'capacity' => $request->capacity,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class created successfully.');
    }

    /**
     * Display the specified class.
     */
    public function show(Classes $class)
    {
        // Fix the relationship loading
        $class->load([
            'sections.teacher', 
            'students.user'
        ]);
        
        $sections = $class->sections;
        $students = $class->students()->with('user')->paginate(10);
        
        // Get routine for this class
        $routines = Routine::where('class_id', $class->id)
            ->with(['section', 'subject', 'teacher'])
            ->orderBy('day_of_week')
            ->orderBy('period')
            ->get()
            ->groupBy('day_of_week');

        return view('backend.classes.show', compact('class', 'sections', 'students', 'routines'));
    }

    /**
     * Show the form for editing the specified class.
     */
    public function edit(Classes $class)
    {
        return view('backend.classes.form', compact('class'));
    }

    /**
     * Update the specified class in storage.
     */
    public function update(Request $request, Classes $class)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:classes,name,' . $class->id,
            'numeric_value' => 'required|integer|min:1|max:12|unique:classes,numeric_value,' . $class->id,
            'shift' => 'required|in:morning,day',
            'capacity' => 'required|integer|min:1|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        $class->update([
            'name' => $request->name,
            'numeric_value' => $request->numeric_value,
            'shift' => $request->shift,
            'capacity' => $request->capacity,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class updated successfully.');
    }

    /**
     * Remove the specified class from storage.
     */
    public function destroy(Classes $class)
    {
        // Check if class has students
        if ($class->students()->count() > 0) {
            return redirect()->route('admin.classes.index')
                ->with('error', 'Cannot delete class that has students. Please transfer students first.');
        }

        // Check if class has sections
        if ($class->sections()->count() > 0) {
            return redirect()->route('admin.classes.index')
                ->with('error', 'Cannot delete class that has sections. Please delete sections first.');
        }

        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class deleted successfully.');
    }

    /**
     * Get sections for a specific class.
     */
    public function getSections(Classes $class)
    {
        $sections = $class->sections()->where('status', 'active')->get();
        
        return response()->json($sections);
    }

    /**
     * Display class routine.
     */
    public function routine(Classes $class, Section $section = null)
    {
        $sections = $class->sections()->where('status', 'active')->get();
        
        $query = Routine::where('class_id', $class->id);
        
        if ($section) {
            $query->where('section_id', $section->id);
        }

        $routines = $query->with(['section', 'subject', 'teacher'])
            ->orderBy('day_of_week')
            ->orderBy('period')
            ->get()
            ->groupBy(['section_id', 'day_of_week']);

        return view('backend.classes.routine', compact('class', 'sections', 'routines', 'section'));
    }

    /**
     * Display class subjects.
     */
    public function subjects(Classes $class)
    {
        $subjects = $class->classSubjects()
            ->with(['subject', 'section', 'teacher'])
            ->get()
            ->groupBy('section_id');

        $sections = $class->sections()->where('status', 'active')->get();

        return view('backend.classes.subjects', compact('class', 'subjects', 'sections'));
    }

    /**
     * Display class students.
     */
    public function students(Classes $class, Request $request)
    {
        $query = $class->students()->with(['user', 'section']);

        if ($request->has('section_id') && $request->section_id) {
            $query->where('section_id', $request->section_id);
        }

        $students = $query->orderBy('roll_number')->paginate(20);
        $sections = $class->sections()->where('status', 'active')->get();

        return view('backend.classes.students', compact('class', 'students', 'sections'));
    }

    /**
     * Get class statistics.
     */
    public function statistics(Classes $class)
    {
        $totalStudents = $class->students()->count();
        $totalSections = $class->sections()->count();
        $totalSubjects = $class->classSubjects()->distinct('subject_id')->count();
        
        // Student gender distribution
        $genderStats = $class->students()
            ->join('users', 'students.user_id', '=', 'users.id')
            ->selectRaw('gender, COUNT(*) as count')
            ->groupBy('gender')
            ->pluck('count', 'gender');

        // Section-wise student distribution
        $sectionStats = $class->sections()
            ->withCount('students')
            ->get()
            ->pluck('students_count', 'name');

        // Blood group distribution
        $bloodGroupStats = $class->students()
            ->selectRaw('blood_group, COUNT(*) as count')
            ->whereNotNull('blood_group')
            ->groupBy('blood_group')
            ->pluck('count', 'blood_group');

        return view('backend.classes.statistics', compact(
            'class',
            'totalStudents',
            'totalSections',
            'totalSubjects',
            'genderStats',
            'sectionStats',
            'bloodGroupStats'
        ));
    }
}