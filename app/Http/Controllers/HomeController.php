<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notice;
use App\Models\Routine;
use App\Models\School;
use App\Models\HealthCard;
use App\Models\TreatmentRequest;
use App\Models\DiaryUpdate;
use App\Models\MedicalRecord;
use App\Models\OnlineExam;
use App\Models\Hospital;
use App\Models\CityCorporationNotice;
use App\Models\VideoConsultation;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user?->student; // null if not logged in

        // School Information
        if ($student) {
            $school = $user->school;
            $schoolId = $user->school_id;
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
                ->where(function ($query) {
                    $query->where('target_roles', 'like', '%student%')
                        ->orWhere('target_roles', 'like', '%all%')
                        ->orWhere('target_roles', 'like', '%guest%');
                })
                ->where(function ($query) {
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
        $class = $section = $todaysSchedule = $todaysClassesCount = $currentPeriod =
            $todayConsultations = $upcomingConsultations = $recentHealthRecords =
            $diaryEntriesCount = $pendingTreatmentRequests = $activeHealthCard =
            $todaysDiaryEntry = $upcomingExams = null;

        $consultationStats = [
            'today_count' => 0,
            'upcoming_count' => 0,
            'completed_count' => 0,
            'pending_payment' => 0,
        ];

        if ($student) {
            $class = $student->class;
            $section = $student->section;

            // Class-specific announcements
            $classAnnouncements = Notice::where('status', 'published')
                ->where(function ($query) use ($class, $section) {
                    if ($class) {
                        $query->where('target_roles', 'like', '%class_' . $class->id . '%')
                            ->orWhere('target_roles', 'like', '%section_' . ($section->id ?? '0') . '%');
                    }
                })
                ->where(function ($query) {
                    $query->where('expiry_date', '>=', now())
                        ->orWhereNull('expiry_date');
                })
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Today's schedule
            $dayOfWeek = strtolower(now()->format('l'));
            $todaysSchedule = Routine::where('class_id', $student->class_id)
                ->where('section_id', $student->section_id)
                ->where('day_of_week', $dayOfWeek)
                ->with(['subject', 'teacher'])
                ->orderBy('period')
                ->get();

            $todaysClassesCount = $todaysSchedule->count();

            $currentTime = now()->format('H:i:s');
            $currentPeriod = $todaysSchedule->first(fn($period) => $currentTime >= $period->start_time && $currentTime <= $period->end_time);

            // Today's video consultations
            $todayConsultations = VideoConsultation::where('user_id', $user->id)
                ->whereDate('scheduled_for', now()->format('Y-m-d'))
                ->whereIn('status', ['scheduled', 'ongoing'])
                ->with('doctor')
                ->orderBy('scheduled_for')
                ->get();

            // Upcoming video consultations
            $upcomingConsultations = VideoConsultation::where('user_id', $user->id)
                ->where('status', 'scheduled')
                ->where('scheduled_for', '>=', now())
                ->with('doctor')
                ->orderBy('scheduled_for')
                ->take(3)
                ->get();

            // Recent health records - using student_id instead of user_id
            $recentHealthRecords = MedicalRecord::where('user_id', $user->id)
                ->orderBy('record_date', 'desc')
                ->take(3)
                ->get();

            $diaryEntriesCount = DiaryUpdate::where('student_id', $student->id)
                ->where('entry_date', '>=', now()->startOfWeek())
                ->count();

            // Treatment requests - using student_id instead of user_id
            $pendingTreatmentRequests = TreatmentRequest::where('student_id', $student->id)
                ->where('status', 'pending')
                ->count();

            $activeHealthCard = HealthCard::where('user_id', $user->id)
                ->where('status', 'active')
                ->where('expiry_date', '>=', now())
                ->first();

            $todaysDiaryEntry = DiaryUpdate::where('student_id', $student->id)
                ->where('entry_date', now()->format('Y-m-d'))
                ->first();

            $upcomingExams = OnlineExam::where('class_id', $student->class_id)
                ->where('exam_date', '>=', now()->format('Y-m-d'))
                ->where('status', 'scheduled')
                ->with('subject')
                ->orderBy('exam_date')
                ->take(3)
                ->get();

            // Video consultation statistics
            $consultationStats = [
                'today_count' => $todayConsultations->count(),
                'upcoming_count' => $upcomingConsultations->count(),
                'completed_count' => VideoConsultation::where('user_id', $user->id)
                    ->where('status', 'completed')
                    ->count(),
                'pending_payment' => VideoConsultation::where('user_id', $user->id)
                    ->where('payment_status', 'pending')
                    ->count(),
            ];
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
            'todayConsultations',
            'upcomingConsultations',
            'recentHealthRecords',
            'diaryEntriesCount',
            'pendingTreatmentRequests',
            'activeHealthCard',
            'todaysDiaryEntry',
            'upcomingExams',
            'student',
            'consultationStats'
        ));
    }
}