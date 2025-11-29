<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Routine;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class RoutineController extends Controller
{
    public function index(Request $request)
    {
        $query = Routine::with(['class', 'section', 'subject', 'teacher']);

        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->has('section_id') && $request->section_id) {
            $query->where('section_id', $request->section_id);
        }

        $routines = $query->orderBy('day_of_week')->orderBy('period')->paginate(50);
        $classes = Classes::active()->get();
        $sections = Section::active()->get();

        return view('backend.routines.index', compact('routines', 'classes', 'sections'));
    }

    public function create()
    {
        $classes = Classes::active()->get();
        $sections = Section::active()->get();
        $subjects = Subject::active()->get();
        $teachers = User::where('role', 'teacher')->where('status', 'active')->get();

        return view('backend.routines.create', compact('classes', 'sections', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day_of_week' => 'required|string',
            'period' => 'required|integer',
            'start_time' => 'required',
            'end_time' => 'required',
            'academic_year' => 'required|digits:4',
        ]);

        // Check for conflicts
        $conflict = Routine::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('day_of_week', $request->day_of_week)
            ->where('period', $request->period)
            ->exists();

        if ($conflict) {
            return back()->with('error', 'A routine already exists for this class, section, day, and period.');
        }

        Routine::create($request->all());

        return redirect()->route('admin.routines.index')
            ->with('success', 'Routine created successfully.');
    }

    public function showByClassSection(Request $request)
    {
        $classId = $request->class_id;
        $sectionId = $request->section_id;

        $routines = Routine::with(['subject', 'teacher'])
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->orderBy('day_of_week')
            ->orderBy('period')
            ->get()
            ->groupBy('day_of_week');

        $classes = Classes::active()->get();
        $sections = Section::active()->get();

        return view('backend.routines.class-view', compact('routines', 'classes', 'sections', 'classId', 'sectionId'));
    }
}