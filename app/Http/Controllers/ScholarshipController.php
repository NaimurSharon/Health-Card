<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\ScholarshipRegistration;
=======
use App\Http\Controllers\Controller;
use App\Models\ScholarshipRegistration;
use App\Models\ScholarshipApplication;
>>>>>>> c356163 (video call ui setup)
use App\Models\ScholarshipExam;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScholarshipController extends Controller
{
<<<<<<< HEAD
    // Show registration form with exam information
    public function showRegistration()
    {
        $user = Auth::user();
        
        if ($user->role === 'student') {
            $student = $user;
        } else {
            return redirect()->route('admin.dashboard');
        }

        // Check if already registered
        $existingRegistration = ScholarshipRegistration::where('student_id', $student->id)
=======
    public function index()
    {
        $student = Auth::user();
        
        $applications = ScholarshipApplication::where('student_id', $student->id)
            ->with('scholarshipExam')
            ->orderBy('application_date', 'desc')
            ->paginate(10);

        return view('frontend.scholarship.index', compact('applications'));
    }
    
        // Show registration form with exam information
    public function showRegistration()
{
    $user = Auth::user();
    
    // If user is logged in and is a student, check if already registered
    if ($user && $user->role === 'student') {
        $existingRegistration = ScholarshipRegistration::where('student_id', $user->id)
>>>>>>> c356163 (video call ui setup)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRegistration) {
            if ($existingRegistration->isApproved()) {
                return redirect()->route('student.scholarship');
            } else {
                return redirect()->route('student.scholarship.status');
            }
        }
<<<<<<< HEAD

        // Get available exams for information
        $availableExams = ScholarshipExam::where('status', 'upcoming')
            ->where('exam_date', '>=', now())
            ->get();

        return view('student.scholarship.register', compact('availableExams'));
    }

=======
    }

    // Get available exams for information
    $availableExams = ScholarshipExam::where('status', 'upcoming')
        ->where('exam_date', '>=', now())
        ->get();

    return view('frontend.scholarship.register', compact('availableExams'));
}

>>>>>>> c356163 (video call ui setup)
    // Submit registration
    public function submitRegistration(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role === 'student') {
            $student = $user;
        } else {
            return redirect()->route('admin.dashboard');
        }

        // Validate registration data
        $request->validate([
<<<<<<< HEAD
            'academic_background' => 'required|string|min:100|max:1000',
            'extracurricular_activities' => 'required|string|min:50|max:500',
            'achievements' => 'required|string|min:50|max:500',
            'reason_for_applying' => 'required|string|min:100|max:1000',
=======
            'academic_background' => 'required|string|min:50|max:1000',
            'extracurricular_activities' => 'required|string|min:50|max:500',
            'achievements' => 'required|string|min:50|max:500',
            'reason_for_applying' => 'required|string|min:50|max:1000',
>>>>>>> c356163 (video call ui setup)
        ]);

        // Check if already registered
        $existingRegistration = ScholarshipRegistration::where('student_id', $student->id)
<<<<<<< HEAD
            ->whereIn('status', ['pending', 'approved'])
=======
            ->whereIn('status', ['pending', 'approved','rejected'])
>>>>>>> c356163 (video call ui setup)
            ->first();

        if ($existingRegistration) {
            return redirect()->route('student.scholarship.status')
                ->with('error', 'You have already submitted a registration.');
        }

        // Create registration
        $registration = ScholarshipRegistration::create([
            'student_id' => $student->id,
            'exam_id' => ScholarshipExam::where('status', 'upcoming')->first()->id, // Register for first upcoming exam
            'academic_background' => $request->academic_background,
            'extracurricular_activities' => $request->extracurricular_activities,
            'achievements' => $request->achievements,
            'reason_for_applying' => $request->reason_for_applying,
            'status' => 'pending',
        ]);

        return redirect()->route('student.scholarship.status')
            ->with('success', 'Your scholarship registration has been submitted successfully! It will be reviewed by the administration.');
    }

    // Show registration status
    public function registrationStatus()
    {
        $user = Auth::user();
        
        if ($user->role === 'student') {
            $student = $user;
        } else {
            return redirect()->route('admin.dashboard');
        }

        $registration = ScholarshipRegistration::where('student_id', $student->id)
            ->with(['exam', 'approver'])
            ->first();

        if (!$registration) {
            return redirect()->route('student.scholarship.register');
        }

<<<<<<< HEAD
        return view('student.scholarship.status', compact('registration'));
=======
        return view('frontend.scholarship.status', compact('registration'));
    }

    public function exams()
    {
        $student = Auth::user();
        
        $upcomingExams = ScholarshipExam::where('status', 'upcoming')
            ->where('exam_date', '>=', today())
            ->orderBy('exam_date')
            ->paginate(10);

        $appliedExamIds = ScholarshipApplication::where('student_id', $student->id)
            ->pluck('scholarship_exam_id')
            ->toArray();

        return view('frontend.scholarship.exams', compact('upcomingExams', 'appliedExamIds'));
    }

    public function apply($examId)
    {
        $student = Auth::user();
        
        $exam = ScholarshipExam::where('id', $examId)
            ->where('status', 'upcoming')
            ->firstOrFail();

        // Check if already applied
        $existingApplication = ScholarshipApplication::where([
            'student_id' => $student->id,
            'scholarship_exam_id' => $examId
        ])->first();

        if ($existingApplication) {
            return redirect()->route('student.scholarship.exams')->with('error', 'You have already applied for this scholarship exam.');
        }

        ScholarshipApplication::create([
            'scholarship_exam_id' => $examId,
            'student_id' => $student->id,
            'application_date' => today(),
            'status' => 'applied',
        ]);

        return redirect()->route('student.scholarship.exams')->with('success', 'Successfully applied for the scholarship exam!');
>>>>>>> c356163 (video call ui setup)
    }
}