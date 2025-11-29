<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Routine;
use Carbon\Carbon;

class TeacherRoutineController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();
        
        try {
            $selectedDay = $request->get('day', strtolower(Carbon::today()->englishDayOfWeek));
            
            $routines = Routine::where('teacher_id', $teacher->id)
                ->where('day_of_week', $selectedDay)
                ->with(['class', 'section', 'subject'])
                ->orderBy('period')
                ->get()
                ->groupBy('class_id');

            $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

            return view('teacher.routine.index', compact('teacher', 'routines', 'days', 'selectedDay'));

        } catch (\Exception $e) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'Unable to load routine data.');
        }
    }

    public function weekly()
    {
        $teacher = Auth::user();
        
        try {
            $weeklyRoutines = Routine::where('teacher_id', $teacher->id)
                ->with(['class', 'section', 'subject'])
                ->orderBy('day_of_week')
                ->orderBy('period')
                ->get()
                ->groupBy(['day_of_week', 'class_id']);

            $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

            return view('teacher.routine.weekly', compact('teacher', 'weeklyRoutines', 'days'));

        } catch (\Exception $e) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'Unable to load weekly routine data.');
        }
    }
}