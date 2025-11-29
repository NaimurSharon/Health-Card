<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Subject;
use App\Models\Classes;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use App\Models\ScholarshipExam;
use Illuminate\Support\Facades\Validator;

class ScholarshipExamController extends Controller
{
    // Show all exams
    public function index()
    {
        $query = ScholarshipExam::with(['class', 'subject', 'attempts']);

        // Search filter
        if (request('search')) {
            $query->where('title', 'like', '%' . request('search') . '%')
                  ->orWhere('description', 'like', '%' . request('search') . '%');
        }

        // Status filter
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Exam date filter
        if (request('exam_date')) {
            $query->where('exam_date', request('exam_date'));
        }

        $exams = $query->latest()->paginate(10);

        return view('backend.exams.index', compact('exams'));
    }

    // Show create form
    public function create()
    {
        $classes = Classes::where('status', 'active')->get();
        $subjects = Subject::where('status', 'active')->get();
        return view('backend.exams.create', compact('classes', 'subjects'));
    }

    // Store new exam
    public function store(Request $request)
    {
        // First validate basic fields
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date',
            'duration_minutes' => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:1|lte:total_marks',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate questions exist
        if (!$request->has('questions') || !is_array($request->questions) || count($request->questions) === 0) {
            return redirect()->back()
                ->withErrors(['questions' => 'At least one question is required.'])
                ->withInput();
        }
        
        // Validate each question
        foreach ($request->questions as $index => $question) {
            $questionValidator = Validator::make($question, [
                'question' => 'required|string',
                'options' => 'required|array|min:4|max:4',
                'options.0' => 'required|string',
                'options.1' => 'required|string',
                'options.2' => 'required|string',
                'options.3' => 'required|string',
                'correct_answer' => 'required|integer|min:0|max:3',
                'marks' => 'required|integer|min:1'
            ]);

            if ($questionValidator->fails()) {
                return redirect()->back()
                    ->withErrors(["questions.{$index}" => "Question " . ($index + 1) . " has errors."])
                    ->withInput();
            }
        }

        // Calculate total marks from questions
        $totalMarksFromQuestions = 0;
        foreach ($request->questions as $question) {
            $totalMarksFromQuestions += (int) $question['marks'];
        }

        // Validate total marks match
        if ($totalMarksFromQuestions != $request->total_marks) {
            return redirect()->back()
                ->withErrors(['total_marks' => "Total marks ({$request->total_marks}) must match the sum of all question marks ({$totalMarksFromQuestions})."])
                ->withInput();
        }

        // Prepare questions data with proper indexing
        $questionsData = [];
        foreach ($request->questions as $question) {
            $questionsData[] = [
                'question' => $question['question'],
                'options' => [
                    $question['options'][0] ?? '',
                    $question['options'][1] ?? '',
                    $question['options'][2] ?? '',
                    $question['options'][3] ?? '',
                ],
                'correct_answer' => (int) $question['correct_answer'],
                'marks' => (int) $question['marks']
            ];
        }

        try {
            $exam = ScholarshipExam::create([
                'title' => $request->title,
                'description' => $request->description,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'exam_date' => $request->exam_date,
                'duration_minutes' => $request->duration_minutes,
                'total_marks' => $request->total_marks,
                'passing_marks' => $request->passing_marks,
                'questions' => json_encode($questionsData),
                'status' => $request->status,
                'created_by' => auth()->id()
            ]);

            return redirect()->route('admin.exams.index')
                ->with('success', 'Exam created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create exam. Please try again.'])
                ->withInput();
        }
    }

    // Show exam details
    public function show(ScholarshipExam $exam)
    {
        $questions = json_decode($exam->questions, true) ?? [];
        $attempts = $exam->attempts()->with('student.user')->get();
        
        return view('backend.exams.show', compact('exam', 'questions', 'attempts'));
    }

    // Show edit form
    public function edit(ScholarshipExam $exam)
    {
        $classes = Classes::where('status', 'active')->get();
        $subjects = Subject::where('status', 'active')->get();
        $questions = json_decode($exam->questions, true) ?? [];
        
        return view('backend.exams.edit', compact('exam', 'classes', 'subjects', 'questions'));
    }

    // Update exam
    public function update(Request $request, ScholarshipExam $exam)
    {
        // First validate basic fields
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date',
            'duration_minutes' => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:1|lte:total_marks',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate questions exist
        if (!$request->has('questions') || !is_array($request->questions) || count($request->questions) === 0) {
            return redirect()->back()
                ->withErrors(['questions' => 'At least one question is required.'])
                ->withInput();
        }

        // Validate each question
        foreach ($request->questions as $index => $question) {
            $questionValidator = Validator::make($question, [
                'question' => 'required|string',
                'options' => 'required|array|min:4|max:4',
                'options.0' => 'required|string',
                'options.1' => 'required|string',
                'options.2' => 'required|string',
                'options.3' => 'required|string',
                'correct_answer' => 'required|integer|min:0|max:3',
                'marks' => 'required|integer|min:1'
            ]);

            if ($questionValidator->fails()) {
                return redirect()->back()
                    ->withErrors(["questions.{$index}" => "Question " . ($index + 1) . " has errors."])
                    ->withInput();
            }
        }

        // Calculate total marks from questions
        $totalMarksFromQuestions = 0;
        foreach ($request->questions as $question) {
            $totalMarksFromQuestions += (int) $question['marks'];
        }

        // Validate total marks match
        if ($totalMarksFromQuestions != $request->total_marks) {
            return redirect()->back()
                ->withErrors(['total_marks' => "Total marks ({$request->total_marks}) must match the sum of all question marks ({$totalMarksFromQuestions})."])
                ->withInput();
        }

        // Prepare questions data with proper indexing
        $questionsData = [];
        foreach ($request->questions as $question) {
            $questionsData[] = [
                'question' => $question['question'],
                'options' => [
                    $question['options'][0] ?? '',
                    $question['options'][1] ?? '',
                    $question['options'][2] ?? '',
                    $question['options'][3] ?? '',
                ],
                'correct_answer' => (int) $question['correct_answer'],
                'marks' => (int) $question['marks']
            ];
        }

        try {
            $exam->update([
                'title' => $request->title,
                'description' => $request->description,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'exam_date' => $request->exam_date,
                'duration_minutes' => $request->duration_minutes,
                'total_marks' => $request->total_marks,
                'status' => $request->status,
                'passing_marks' => $request->passing_marks,
                'questions' => json_encode($questionsData),
            ]);

            return redirect()->route('admin.exams.index')
                ->with('success', 'Exam updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update exam. Please try again.'])
                ->withInput();
        }
    }

    // Change exam status
    public function updateStatus(ScholarshipExam $exam, Request $request)
    {
        $request->validate([
            'status' => 'required|in:upcoming,ongoing,completed,cancelled'
        ]);

        $exam->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Exam status updated successfully!');
    }

    // Delete exam
    public function destroy(ScholarshipExam $exam)
    {
        try {
            // Delete related attempts first
            $exam->attempts()->delete();
            $exam->delete();
            
            return redirect()->route('admin.exams.index')
                ->with('success', 'Exam deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete exam. Please try again.']);
        }
    }

    // Show exam results
    public function results(ScholarshipExam $exam)
    {
        $attempts = $exam->attempts()
            ->with('student.user')
            ->where('status', 'graded')
            ->orderBy('score', 'desc')
            ->paginate(20);
            
        return view('backend.exams.results', compact('exam', 'attempts'));
    }

    // Show applicants
    public function applicants(ScholarshipExam $exam)
    {
        $attempts = $exam->attempts()
            ->with('student.user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('backend.exams.applicants', compact('exam', 'attempts'));
    }

    // Delete exam attempt
    public function destroyAttempt(ExamAttempt $attempt)
    {
        try {
            $attempt->delete();
            return redirect()->back()->with('success', 'Exam attempt deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete attempt. Please try again.']);
        }
    }
}