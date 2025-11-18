<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\MedicalRecord;
use App\Models\Classes;
use App\Models\CityCorporationNotice;
use App\Models\User;
use App\Models\Notice;
use App\Models\Attendance;
use App\Models\FeePayment;
use App\Models\Appointment;
use App\Models\TreatmentRequest;
use App\Models\HealthCard;
use App\Models\Routine;
use App\Models\DiaryUpdate;
use App\Models\OnlineExam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfWeek = $today->copy()->startOfWeek();
        $startOfMonth = $today->copy()->startOfMonth();

        // Main Statistics
        $stats = [
            'total_students' => Student::count(),
            'total_teachers' => User::where('role', 'teacher')->where('status', 'active')->count(),
            'total_classes' => Classes::active()->count(),
            'total_staff' => User::where('role', 'staff')->where('status', 'active')->count(),
        ];

        // Today's Overview
        $todayOverview = [
            'present_today' => 69,
            'absent_today' => 69,
            'new_students_this_month' => Student::where('created_at', '>=', $startOfMonth)->count(),
            'pending_payments' => 6969,
        ];

        // Medical Statistics
        $medicalStats = [
            'emergencies_this_week' => MedicalRecord::emergency()
                ->where('record_date', '>=', $startOfWeek)
                ->count(),
            'total_medical_records' => MedicalRecord::count(),
            'pending_follow_ups' => MedicalRecord::where('follow_up_date', '>=', $today)
                ->count(),
        ];

        // Recent Activities
        $recentActivities = [
            'medical_records' => MedicalRecord::with('student.user')
                ->recent(7)
                ->orderBy('record_date', 'desc')
                ->limit(6)
                ->get(),
        ];


        // Monthly student growth (last 6 months)
        $monthlyGrowth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Student::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $monthlyGrowth[] = [
                'month' => $month->format('M Y'),
                'students' => $count
            ];
        }

        return view('backend.dashboard', compact(
            'stats',
            'todayOverview',
            'medicalStats',
            'recentActivities',
            'monthlyGrowth'
        ));
    }
    
    public function doctorIndex()
    {
        $doctorId = auth()->id();
        $today = Carbon::today();
        $startOfWeek = $today->copy()->startOfWeek();

        // Today's appointments
        $todaysAppointments = Appointment::with('student.user')
            ->forDoctor($doctorId)
            ->today()
            ->scheduled()
            ->orderBy('appointment_time')
            ->get();

        // Upcoming appointments
        $upcomingAppointments = Appointment::with('student.user')
            ->forDoctor($doctorId)
            ->upcoming()
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();

        // Pending treatment requests
        $pendingRequests = TreatmentRequest::with('student.user')
            ->forDoctor($doctorId)
            ->pending()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Statistics
        $stats = [
            'today_appointments' => $todaysAppointments->count(),
            'weekly_appointments' => Appointment::forDoctor($doctorId)
                ->whereBetween('appointment_date', [$startOfWeek, $startOfWeek->copy()->endOfWeek()])
                ->scheduled()
                ->count(),
            'pending_requests' => TreatmentRequest::forDoctor($doctorId)->pending()->count(),
            'total_patients' => Student::whereHas('medicalRecords', function($query) use ($doctorId) {
                $query->recordedByDoctor($doctorId);
            })->count(),
        ];

        // Recent medical records
        $recentRecords = MedicalRecord::with('student.user')
            ->recordedByDoctor($doctorId)
            ->recent(7)
            ->orderBy('record_date', 'desc')
            ->limit(5)
            ->get();

        return view('doctor.dashboard', compact(
            'stats',
            'todaysAppointments',
            'upcomingAppointments',
            'pendingRequests',
            'recentRecords'
        ));
    }
    
    public function studentIndex()
    {
        $student = Auth::user();
        $studentDetails = $student->student;
    
    
        // School Information
        $school = $student->school;
    
        $schoolId = $student->school_id;
    
        $cityCorporationNotices = CityCorporationNotice::active()
            ->forSchool($schoolId)
            ->forRoles(['student'])
            ->latest()
            ->take(5)
            ->get();
    
        // Class Information
        $class = $studentDetails->class;
        $section = $studentDetails->section;
    
        // School Notices
        $schoolNotices = Notice::where('status', 'published')
            ->where(function($query) {
                $query->where('target_roles', 'like', '%student%')
                      ->orWhere('target_roles', 'like', '%all%');
            })
            ->where(function($query) {
                $query->where('expiry_date', '>=', now())
                      ->orWhereNull('expiry_date');
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    
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
    
        // Current period
        $currentTime = now()->format('H:i:s');
        $currentPeriod = $todaysSchedule->first(function($period) use ($currentTime) {
            return $currentTime >= $period->start_time && $currentTime <= $period->end_time;
        });
    
        // Upcoming appointments
        $upcomingAppointments = Appointment::where('student_id', $studentDetails->id)
            ->where('status', 'scheduled')
            ->where('appointment_date', '>=', now()->format('Y-m-d'))
            ->with('doctor')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(3)
            ->get();
    
        // Recent health records
        $recentHealthRecords = MedicalRecord::where('student_id', $studentDetails->id)
            ->orderBy('record_date', 'desc')
            ->take(3)
            ->get();
    
        // Diary entries count
        $diaryEntriesCount = DiaryUpdate::where('student_id', $studentDetails->id)
            ->where('entry_date', '>=', now()->startOfWeek())
            ->count();
    
        // Pending treatment requests
        $pendingTreatmentRequests = TreatmentRequest::where('student_id', $studentDetails->id)
            ->where('status', 'pending')
            ->count();
    
        // Active health card
        $activeHealthCard = HealthCard::where('student_id', $studentDetails->id)
            ->where('status', 'active')
            ->where('expiry_date', '>=', now())
            ->first();
    
        // Today's diary entry
        $todaysDiaryEntry = DiaryUpdate::where('student_id', $studentDetails->id)
            ->where('entry_date', now()->format('Y-m-d'))
            ->first();
    
        // Upcoming exams
        $upcomingExams = OnlineExam::where('class_id', $studentDetails->class_id)
            ->where('exam_date', '>=', now()->format('Y-m-d'))
            ->where('status', 'scheduled')
            ->with('subject')
            ->orderBy('exam_date')
            ->take(3)
            ->get();
    
        return view('student.dashboard', compact(
            'school',
            'class',
            'section',
            'schoolNotices',
            'classAnnouncements',
            'todaysSchedule',
            'todaysClassesCount',
            'currentPeriod',
            'upcomingAppointments',
            'cityCorporationNotices',
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