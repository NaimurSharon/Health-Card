<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipExam;
use App\Models\ScholarshipRegistration;
use App\Models\ExamAttempt;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    // Show available exams for student
    public function studentExams()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role === 'student') {
            $student = $user;
        } else {
            return redirect()->route('admin.dashboard')->with('error', 'Student profile not found.');
        }
        
        $hasApprovedRegistration = ScholarshipRegistration::where('student_id', $student->id)
            ->where('status', 'approved')
            ->exists();
            
        if (!$hasApprovedRegistration) {
            return redirect()->route('student.scholarship.register')
                ->with('error', 'You need to complete scholarship registration and get approval before accessing exams.');
        }
        
        $exams = ScholarshipExam::where('status', 'ongoing')
            ->where('exam_date', today())
            ->whereDoesntHave('attempts', function($query) use ($student) {
                $query->where('student_id', $student->id)
                      ->whereIn('status', ['submitted', 'graded']);
            })
            ->with(['attempts' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->paginate(9);

        return view('student.exams.index', compact('exams'));
    }

    // Start exam
    public function startExam(ScholarshipExam $exam)
    {
        $user = Auth::user();
        
        if ($user->role === 'student') {
            $student = $user;
        } else {
            return redirect()->route('admin.dashboard')->with('error', 'Student profile not found.');
        }
        
        $hasApprovedRegistration = ScholarshipRegistration::where('student_id', $student->id)
            ->where('status', 'approved')
            ->exists();
            
        if (!$hasApprovedRegistration) {
            return redirect()->route('student.scholarship.register')
                ->with('error', 'You need to complete scholarship registration and get approval before accessing exams.');
        }

        // Check if exam is active
        if (!$exam->isActive()) {
            return redirect()->route('student.scholarship')->with('error', 'This exam is not currently active.');
        }

        // Check if already attempted
        $existingAttempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->whereIn('status', ['in_progress', 'submitted', 'graded'])
            ->first();

        if ($existingAttempt) {
            if ($existingAttempt->status === 'in_progress') {
                return redirect()->route('student.exam.take', $existingAttempt);
            } else {
                return redirect()->route('student.exam.result', $existingAttempt);
            }
        }

        // Create new attempt
        $attempt = ExamAttempt::create([
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'started_at' => now(),
            'status' => 'in_progress',
            'answers' => [],
            'time_remaining' => $exam->duration_minutes * 60
        ]);

        return redirect()->route('student.exam.take', $attempt);
    }

    public function takeExam(ExamAttempt $attempt)
    {
        $user = Auth::user();
        $exam = $attempt->exam;
    
        if ($user->role === 'student') {
            $student = $user;
        } else {
            return redirect()->route('admin.dashboard')->with('error', 'Student profile not found.');
        }
    
        $hasApprovedRegistration = ScholarshipRegistration::where('student_id', $student->id)
            ->where('status', 'approved')
            ->exists();
            
        if (!$hasApprovedRegistration) {
            return redirect()->route('student.scholarship.register')
                ->with('error', 'You need to complete scholarship registration and get approval before accessing exams.');
        }
    
        if ($attempt->status !== 'in_progress') {
            return redirect()->route('student.exam.result', $attempt);
        }
    
        // Calculate time remaining for entire exam
        $elapsed = now()->diffInSeconds($attempt->started_at);
        $totalTime = $exam->duration_minutes * 60;
        $timeRemaining = max(0, $totalTime - $elapsed);
    
        // Update time remaining in database
        $attempt->update(['time_remaining' => $timeRemaining]);
    
        // Auto-submit if time's up
        if ($timeRemaining <= 0) {
            return $this->submitExam($attempt);
        }
    
        // Get current question index from request or calculate
        $currentQuestionIndex = request('question', $attempt->getCurrentQuestionIndex());
        $totalQuestions = $exam->total_questions;
    
        // Check if exam completed
        if ($currentQuestionIndex >= $totalQuestions) {
            return $this->submitExam($attempt);
        }
    
        // Get current question
        $questions = json_decode($exam->questions, true) ?? [];
        
        if (!isset($questions[$currentQuestionIndex])) {
            return $this->submitExam($attempt);
        }
    
        $currentQuestion = $questions[$currentQuestionIndex];
    
        // Remove $questionTimeRemaining since we're not using per-question timing
        return view('student.exams.take', compact(
            'attempt', 
            'exam', 
            'currentQuestion', 
            'currentQuestionIndex', 
            'totalQuestions',
            'timeRemaining'
        ));
    }

    // Submit answer with tab switch penalty support
    public function submitAnswer(ExamAttempt $attempt, Request $request)
    {
        $user = Auth::user();
        
        if ($user->role === 'student') {
            $student = $user;
        } else {
            return response()->json(['success' => false, 'message' => 'Unauthorized access']);
        }
        
        if ($attempt->status !== 'in_progress') {
            return response()->json(['success' => false, 'message' => 'Exam already submitted']);
        }
    
        $questionIndex = $request->input('question_index');
        $answer = $request->input('answer');
        $isTabSwitchPenalty = $request->input('is_tab_switch_penalty', false);
    
        // Validate question index
        if (!is_numeric($questionIndex) || $questionIndex < 0) {
            return response()->json(['success' => false, 'message' => 'Invalid question index']);
        }
    
        // For tab switch penalties, allow -1 as answer
        if ($isTabSwitchPenalty) {
            $answer = -1;
        } else {
            // Validate normal answer - allow 0-3
            if (!is_numeric($answer) || $answer < 0 || $answer > 3) {
                return response()->json(['success' => false, 'message' => 'Invalid answer']);
            }
        }
    
        try {
            // Get current answers as array
            $answers = $attempt->answers;
            if (!is_array($answers)) {
                $answers = [];
            }
            
            // Ensure the answers array has the right length
            while (count($answers) <= $questionIndex) {
                $answers[] = null;
            }
            
            // Update the answer
            $answers[$questionIndex] = (int)$answer;
            
            // Save
            $attempt->update(['answers' => $answers]);
    
    
            return response()->json(['success' => true]);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
    }
    
    // Force move to next question (for tab switch penalties)
    public function forceNextQuestion(ExamAttempt $attempt, $currentQuestionIndex)
    {
        $user = Auth::user();
        
        if ($user->role === 'student') {
            $student = $user;
        } else {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }
        
        if ($attempt->status !== 'in_progress') {
            return response()->json(['success' => false, 'message' => 'Exam already submitted']);
        }
    
        // Submit wrong answer for current question due to tab switching
        $answers = $attempt->answers;
        if (!is_array($answers)) {
            $answers = [];
        }
        
        // Ensure the answers array has the right length
        while (count($answers) <= $currentQuestionIndex) {
            $answers[] = null;
        }
        
        $answers[$currentQuestionIndex] = -1; // -1 indicates tab switch penalty
        $attempt->update(['answers' => $answers]);
    
        // Move to next question or submit exam
        $totalQuestions = $attempt->exam->total_questions;
        if ($currentQuestionIndex + 1 >= $totalQuestions) {
            $this->submitExam($attempt);
            return response()->json([
                'success' => true, 
                'redirect_url' => route('student.exam.result', $attempt)
            ]);
        } else {
            return response()->json([
                'success' => true, 
                'redirect_url' => route('student.exam.take', $attempt) . '?question=' . ($currentQuestionIndex + 1)
            ]);
        }
    }

    // Auto-submit exam when time runs out
    public function autoSubmit(Request $request, ExamAttempt $attempt)
    {
        $user = Auth::user();
        
        if ($user->role === 'student') {
            $student = $user;
        } else {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }
        
        // Verify ownership
        if ($attempt->student_id !== $student->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access']);
        }

        // Update answers if provided
        if ($request->has('answers')) {
            $answers = $request->input('answers');
            if (is_array($answers)) {
                $attempt->update(['answers' => $answers]);
            }
        }

        // Submit the exam
        return $this->submitExam($attempt, true);
    }

    // Manual exam submission
    public function submitExam(ExamAttempt $attempt, $isJsonResponse = false)
    {
        $exam = $attempt->exam;
        
        // Calculate score
        $score = $attempt->calculateScore();
        
        // Update attempt
        $attempt->update([
            'submitted_at' => now(),
            'score' => $score,
            'status' => 'graded',
            'time_remaining' => 0
        ]);

        if ($isJsonResponse) {
            return response()->json([
                'success' => true, 
                'redirect_url' => route('student.exam.result', $attempt)
            ]);
        }

        return redirect()->route('student.exam.result', $attempt);
    }

    // Show exam result
    public function showResult(ExamAttempt $attempt)
    {
        $user = Auth::user();
        if ($user->role === 'student' || $user->role === 'admin') {
            $student = $user;
        } else {
            return redirect()->route('admin.dashboard')->with('error', 'Student profile not found.');
        }

        // Verify ownership for students
        // if ($user->role === 'student' && $attempt->student_id !== $student->id) {
        //     abort(403, 'Unauthorized access');
        // }

        $exam = $attempt->exam;
        $passed = $attempt->score >= $exam->passing_marks;

        return view('student.exams.result', compact('attempt', 'exam', 'passed'));
    }

    // Get exam details
    public function examDetails(ScholarshipExam $exam)
    {
        return response()->json([
            'title' => $exam->title,
            'description' => $exam->description,
            'duration_minutes' => $exam->duration_minutes,
            'total_marks' => $exam->total_marks,
            'passing_marks' => $exam->passing_marks,
            'total_questions' => $exam->total_questions
        ]);
    }
}