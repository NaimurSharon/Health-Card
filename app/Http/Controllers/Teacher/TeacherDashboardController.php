<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassDiary;
use App\Models\Routine;
use App\Models\ClassSubject;
use Carbon\Carbon;

class TeacherDashboardController extends Controller
{
    public function dashboard()
    {
        $teacher = Auth::user();
        
        try {
            // Get today's routines
            $today = Carbon::today();
            $dayOfWeek = strtolower($today->englishDayOfWeek);
            
            $todayRoutines = Routine::where('teacher_id', $teacher->id)
                ->where('day_of_week', $dayOfWeek)
                ->with(['class', 'section', 'subject'])
                ->orderBy('period')
                ->get();

            // Get upcoming classes (next 2 hours)
            $currentTime = Carbon::now();
            $nextTwoHours = $currentTime->copy()->addHours(2);
            
            $upcomingClasses = Routine::where('teacher_id', $teacher->id)
                ->where('day_of_week', $dayOfWeek)
                ->whereTime('start_time', '>=', $currentTime->format('H:i:s'))
                ->whereTime('start_time', '<=', $nextTwoHours->format('H:i:s'))
                ->with(['class', 'section', 'subject'])
                ->orderBy('start_time')
                ->get();

            // Get assigned classes and subjects
            $assignedSubjects = ClassSubject::where('teacher_id', $teacher->id)
                ->with(['class', 'section', 'subject'])
                ->get()
                ->groupBy('class_id');

            // Get recent homeworks
            $recentHomeworks = ClassDiary::where('teacher_id', $teacher->id)
                ->with(['class', 'section', 'subject'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Statistics
            $stats = [
                'total_classes_today' => $todayRoutines->count(),
                'upcoming_classes' => $upcomingClasses->count(),
                'assigned_subjects' => ClassSubject::where('teacher_id', $teacher->id)->distinct('subject_id')->count(),
                'total_sections' => ClassSubject::where('teacher_id', $teacher->id)->distinct('section_id')->count(),
            ];

            return view('teacher.dashboard', compact(
                'teacher',
                'todayRoutines',
                'upcomingClasses',
                'assignedSubjects',
                'recentHomeworks',
                'stats',
                'today'
            ));

        } catch (\Exception $e) {
            return view('teacher.dashboard', [
                'teacher' => $teacher,
                'todayRoutines' => collect(),
                'upcomingClasses' => collect(),
                'assignedSubjects' => collect(),
                'recentHomeworks' => collect(),
                'stats' => [],
                'today' => Carbon::today(),
                'error' => 'Unable to load dashboard data. Please try again later.'
            ]);
        }
    }

    public function assignedClasses()
    {
        $teacher = Auth::user();
        
        try {
            $assignedClasses = ClassSubject::where('teacher_id', $teacher->id)
                ->with(['class', 'section', 'subject'])
                ->get()
                ->groupBy('class_id');

            $classStats = [];
            foreach ($assignedClasses as $classId => $subjects) {
                $class = $subjects->first()->class;
                $classStats[$classId] = [
                    'total_sections' => $subjects->unique('section_id')->count(),
                    'total_subjects' => $subjects->unique('subject_id')->count(),
                    'total_students' => \App\Models\Student::where('class_id', $classId)->count(),
                ];
            }

            return view('teacher.assigned-classes', compact('teacher', 'assignedClasses', 'classStats'));

        } catch (\Exception $e) {
            return view('teacher.assigned-classes', [
                'teacher' => $teacher,
                'assignedClasses' => collect(),
                'classStats' => [],
                'error' => 'Unable to load assigned classes data.'
            ]);
        }
    }
}