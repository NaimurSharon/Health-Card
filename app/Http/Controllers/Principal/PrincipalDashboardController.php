<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Notice;
use App\Models\Routine;
use App\Models\ClassDiary;
use App\Models\ClassSubject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrincipalDashboardController extends Controller
{
    public function dashboard()
    {
        $teacher = Auth::user();
        $school = auth()->user()->school;
        $today = Carbon::today();
        
        // Get statistics
        $stats = [
            'total_students' => Student::where('school_id', $school->id)->count(),
            'total_teachers' => Teacher::where('school_id', $school->id)->count(),
            'total_classes' => Classes::where('school_id', $school->id)->count(),
            'total_sections' => Section::where('school_id', $school->id)->count(),
            'total_classes_today' => 0, // Placeholder for principal
            'upcoming_classes' => 0, // Placeholder for principal
            'assigned_subjects' => 0, // Placeholder for principal
        ];
        
        // Get recent notices
        $recentNotices = Notice::where('school_id', $school->id)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get recent homeworks
        $recentHomeworks = ClassDiary::with(['class', 'section', 'subject'])
            ->where('school_id', $school->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get class distribution
        $classDistribution = Student::select('class_id', DB::raw('count(*) as total'))
            ->where('school_id', $school->id)
            ->groupBy('class_id')
            ->with('class')
            ->get();

        // For principal dashboard, we'll show different data
        $upcomingClasses = collect([]); // Empty for principal
        $todayRoutines = collect([]); // Empty for principal
        $assignedSubjects = collect([]); // Empty for principal

        return view('principal.dashboard', compact(
            'stats', 
            'recentNotices', 
            'recentHomeworks', 
            'classDistribution', 
            'school',
            'teacher',
            'today',
            'upcomingClasses',
            'todayRoutines',
            'assignedSubjects'
        ));
    }
    
    public function assignedClasses()
    {
        $school = auth()->user()->school;
        $classes = Classes::with(['sections', 'students'])
            ->where('school_id', $school->id)
            ->get();
            
        return view('principal.assigned-classes', compact('classes'));
    }
}