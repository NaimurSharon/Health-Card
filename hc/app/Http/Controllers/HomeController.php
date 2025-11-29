<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notice;
use App\Models\Routine;
use App\Models\School;
use App\Models\HealthTip;
use App\Models\HealthCard;
use App\Models\TreatmentRequest;
use App\Models\DiaryUpdate;
use App\Models\MedicalRecord;
use App\Models\OnlineExam;
use App\Models\Hospital;
use App\Models\CityCorporationNotice;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $studentDetails = $student?->student; // null if not logged in
    
        // School Information
        if ($studentDetails) {
            $school = $student->school;
            $schoolId = $student->school_id;
        } else {
            // For guests, show a default school (first active school or the one you want)
            $school = School::inRandomOrder()->first();
            $schoolId = $school?->id;

        }
    
        // Notices
        $cityCorporationNotices = $schoolId
            ? CityCorporationNotice::active()
                ->forSchool($schoolId)
                ->forRoles(['student', 'guest'])
                ->latest()
                ->take(5)
                ->get()
            : collect();
    
        $schoolNotices = $schoolId
            ? Notice::where('status', 'published')
                ->where(function($query) {
                    $query->where('target_roles', 'like', '%student%')
                          ->orWhere('target_roles', 'like', '%all%')
                          ->orWhere('target_roles', 'like', '%guest%');
                })
                ->where(function($query) {
                    $query->where('expiry_date', '>=', now())
                          ->orWhereNull('expiry_date');
                })
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
            : collect();
            
        $hospitals = Hospital::where('status', 'active')
            ->orderBy('type')
            ->orderBy('name')
            ->get();
    
        // Student-specific data only if logged in
        $class = $section = $todaysSchedule = $todaysClassesCount = $currentPeriod = $upcomingAppointments = $recentHealthRecords = $diaryEntriesCount = $pendingTreatmentRequests = $activeHealthCard = $todaysDiaryEntry = $upcomingExams = null;
    
        if ($studentDetails) {
            $class = $studentDetails->class;
            $section = $studentDetails->section;
    
            // Class-specific announcements
            $classAnnouncements = Notice::where('status', 'published')
                ->where(function($query) use ($class, $section) {
                    if ($class) {
                        $query->where('target_roles', 'like', '%class_' . $class->id . '%')
                              ->orWhere('target_roles', 'like', '%section_' . ($section->id ?? '0') . '%');
                    }
                })
                ->where(function($query) {
                    $query->where('expiry_date', '>=', now())
                          ->orWhereNull('expiry_date');
                })
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
    
            // Today's schedule
            $dayOfWeek = strtolower(now()->format('l'));
            $todaysSchedule = Routine::where('class_id', $studentDetails->class_id)
                ->where('section_id', $studentDetails->section_id)
                ->where('day_of_week', $dayOfWeek)
                ->with(['subject', 'teacher'])
                ->orderBy('period')
                ->get();
    
            $todaysClassesCount = $todaysSchedule->count();
    
            $currentTime = now()->format('H:i:s');
            $currentPeriod = $todaysSchedule->first(fn($period) => $currentTime >= $period->start_time && $currentTime <= $period->end_time);
    
            $upcomingAppointments = Appointment::where('student_id', $studentDetails->id)
                ->where('status', 'scheduled')
                ->where('appointment_date', '>=', now()->format('Y-m-d'))
                ->with('doctor')
                ->orderBy('appointment_date')
                ->orderBy('appointment_time')
                ->take(3)
                ->get();
    
            $recentHealthRecords = MedicalRecord::where('student_id', $studentDetails->id)
                ->orderBy('record_date', 'desc')
                ->take(3)
                ->get();
    
            $diaryEntriesCount = DiaryUpdate::where('student_id', $studentDetails->id)
                ->where('entry_date', '>=', now()->startOfWeek())
                ->count();
    
            $pendingTreatmentRequests = TreatmentRequest::where('student_id', $studentDetails->id)
                ->where('status', 'pending')
                ->count();
    
            $activeHealthCard = HealthCard::where('student_id', $studentDetails->id)
                ->where('status', 'active')
                ->where('expiry_date', '>=', now())
                ->first();
                
            $todaysDiaryEntry = DiaryUpdate::where('student_id', $studentDetails->id)
                ->where('entry_date', now()->format('Y-m-d'))
                ->first();
    
            $upcomingExams = OnlineExam::where('class_id', $studentDetails->class_id)
                ->where('exam_date', '>=', now()->format('Y-m-d'))
                ->where('status', 'scheduled')
                ->with('subject')
                ->orderBy('exam_date')
                ->take(3)
                ->get();
        }
    
        return view('frontend.home', compact(
            'school',
            'class',
            'section',
            'schoolNotices',
            'cityCorporationNotices',
            'todaysSchedule',
            'todaysClassesCount',
            'hospitals',
            'currentPeriod',
            'upcomingAppointments',
            'recentHealthRecords',
            'diaryEntriesCount',
            'pendingTreatmentRequests',
            'activeHealthCard',
            'todaysDiaryEntry',
            'upcomingExams',
            'studentDetails'
        ));
    }

}