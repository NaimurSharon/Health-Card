<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipRegistration;
use App\Models\ScholarshipExam;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScholarshipController extends Controller
{
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
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRegistration) {
            if ($existingRegistration->isApproved()) {
                return redirect()->route('student.scholarship');
            } else {
                return redirect()->route('student.scholarship.status');
            }
        }

        // Get available exams for information
        $availableExams = ScholarshipExam::where('status', 'upcoming')
            ->where('exam_date', '>=', now())
            ->get();

        return view('student.scholarship.register', compact('availableExams'));
    }

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
            'academic_background' => 'required|string|min:100|max:1000',
            'extracurricular_activities' => 'required|string|min:50|max:500',
            'achievements' => 'required|string|min:50|max:500',
            'reason_for_applying' => 'required|string|min:100|max:1000',
        ]);

        // Check if already registered
        $existingRegistration = ScholarshipRegistration::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'approved'])
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

        return view('student.scholarship.status', compact('registration'));
    }
}