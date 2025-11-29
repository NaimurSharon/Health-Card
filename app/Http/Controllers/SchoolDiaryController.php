<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolDiaryController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $studentDetails = $student->student;
        
        
        if (!$studentDetails) {
            return view('student.school-diary.index', [
                'homeworks' => collect(),
                'todaysHomeworks' => collect(),
                'upcomingHomeworks' => collect(),
                'studentDetails' => null,
                'error' => 'Student details not found. Please contact administration.'
            ]);
        }

        try {
            $homeworks = \App\Models\ClassDiary::where('class_id', $studentDetails->class_id)
                ->where('section_id', $studentDetails->section_id)
                ->with(['teacher', 'subject'])
                ->orderBy('entry_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $todaysHomeworks = \App\Models\ClassDiary::where('class_id', $studentDetails->class_id)
                ->where('section_id', $studentDetails->section_id)
                ->where('entry_date', today())
                ->where('status', 'active')
                ->with(['teacher', 'subject'])
                ->get();

            $upcomingHomeworks = \App\Models\ClassDiary::where('class_id', $studentDetails->class_id)
                ->where('section_id', $studentDetails->section_id)
                ->where('due_date', '>=', today())
                ->where('status', 'active')
                ->with(['teacher', 'subject'])
                ->orderBy('due_date')
                ->take(5)
                ->get();

            return view('student.school-diary.index', compact(
                'homeworks', 
                'todaysHomeworks', 
                'upcomingHomeworks',
                'studentDetails'
            ));

        } catch (\Exception $e) {
            return view('student.school-diary.index', [
                'homeworks' => collect(),
                'todaysHomeworks' => collect(),
                'upcomingHomeworks' => collect(),
                'studentDetails' => $studentDetails,
                'error' => 'Unable to load homework data. Please try again later.'
            ]);
        }
    }

    public function show($id)
    {
        $student = Auth::user();
        $studentDetails = $student->student;
        
        if (!$studentDetails) {
            abort(404, 'Student details not found');
        }

        try {
            $homework = \App\Models\ClassDiary::where('id', $id)
                ->where('class_id', $studentDetails->class_id)
                ->where('section_id', $studentDetails->section_id)
                ->with(['teacher', 'subject', 'class', 'section'])
                ->firstOrFail();

            return view('student.school-diary.show', compact('homework', 'studentDetails'));

        } catch (\Exception $e) {
            abort(404, 'Homework not found or you do not have access to view it.');
        }
    }

    public function today()
    {
        $student = Auth::user();
        $studentDetails = $student->student;
        
        if (!$studentDetails) {
            return view('student.school-diary.today', [
                'homeworks' => collect(),
                'studentDetails' => null,
                'error' => 'Student details not found. Please contact administration.'
            ]);
        }

        try {
            $homeworks = \App\Models\ClassDiary::where('class_id', $studentDetails->class_id)
                ->where('section_id', $studentDetails->section_id)
                ->where('entry_date', today())
                ->where('status', 'active')
                ->with(['teacher', 'subject'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('student.school-diary.today', compact('homeworks', 'studentDetails'));

        } catch (\Exception $e) {
            return view('student.school-diary.today', [
                'homeworks' => collect(),
                'studentDetails' => $studentDetails,
                'error' => 'Unable to load today\'s homework. Please try again later.'
            ]);
        }
    }

    public function upcoming()
    {
        $student = Auth::user();
        $studentDetails = $student->student;
        
        if (!$studentDetails) {
            return view('student.school-diary.upcoming', [
                'homeworks' => collect(),
                'studentDetails' => null,
                'error' => 'Student details not found. Please contact administration.'
            ]);
        }

        try {
            $homeworks = \App\Models\ClassDiary::where('class_id', $studentDetails->class_id)
                ->where('section_id', $studentDetails->section_id)
                ->where('due_date', '>=', today())
                ->where('status', 'active')
                ->with(['teacher', 'subject'])
                ->orderBy('due_date')
                ->paginate(10);

            return view('student.school-diary.upcoming', compact('homeworks', 'studentDetails'));

        } catch (\Exception $e) {
            return view('student.school-diary.upcoming', [
                'homeworks' => collect(),
                'studentDetails' => $studentDetails,
                'error' => 'Unable to load upcoming homework. Please try again later.'
            ]);
        }
    }
    
    // Print function for homework
    public function printHomework(Request $request)
    {
        $student = Auth::user();
        $studentDetails = $student->student;
        
        if (!$studentDetails) {
            abort(404, 'Student details not found');
        }

        try {
            $filterDate = $request->get('date', today()->format('Y-m-d'));
            
            $homeworks = \App\Models\ClassDiary::where('class_id', $studentDetails->class_id)
                ->where('section_id', $studentDetails->section_id)
                ->where('entry_date', $filterDate)
                ->with(['teacher', 'subject'])
                ->orderBy('created_at', 'desc')
                ->get();

            $data = [
                'homeworks' => $homeworks,
                'student' => $student,
                'studentDetails' => $studentDetails,
                'filterDate' => $filterDate,
                'printDate' => now()->format('F j, Y g:i A')
            ];

            return view('student.school-diary.print', $data);

        } catch (\Exception $e) {
            abort(500, 'Unable to generate print view. Please try again later.');
        }
    }

    // Download PDF function
    public function downloadPdf(Request $request)
    {
        $student = Auth::user();
        $studentDetails = $student->student;
        
        if (!$studentDetails) {
            abort(404, 'Student details not found');
        }

        try {
            $filterDate = $request->get('date', today()->format('Y-m-d'));
            
            $homeworks = \App\Models\ClassDiary::where('class_id', $studentDetails->class_id)
                ->where('section_id', $studentDetails->section_id)
                ->where('entry_date', $filterDate)
                ->with(['teacher', 'subject'])
                ->orderBy('created_at', 'desc')
                ->get();

            $data = [
                'homeworks' => $homeworks,
                'student' => $student,
                'studentDetails' => $studentDetails,
                'filterDate' => $filterDate,
                'printDate' => now()->format('F j, Y g:i A')
            ];

            $pdf = Pdf::loadView('student.school-diary.pdf', $data);
            
            $filename = 'homework-' . $filterDate . '-' . $studentDetails->student_id . '.pdf';
            
            return $pdf->download($filename);

        } catch (\Exception $e) {
            abort(500, 'Unable to generate PDF. Please try again later.');
        }
    }

    // Download homework details as PDF
    public function downloadHomeworkPdf($id)
    {
        $student = Auth::user();
        $studentDetails = $student->student;
        
        if (!$studentDetails) {
            abort(404, 'Student details not found');
        }

        try {
            $homework = \App\Models\ClassDiary::where('id', $id)
                ->where('class_id', $studentDetails->class_id)
                ->where('section_id', $studentDetails->section_id)
                ->with(['teacher', 'subject', 'class', 'section'])
                ->firstOrFail();

            $data = [
                'homework' => $homework,
                'student' => $student,
                'studentDetails' => $studentDetails,
                'printDate' => now()->format('F j, Y g:i A')
            ];

            $pdf = Pdf::loadView('student.school-diary.homework-pdf', $data);
            
            $filename = 'homework-' . $homework->id . '-' . $studentDetails->student_id . '.pdf';
            
            return $pdf->download($filename);

        } catch (\Exception $e) {
            abort(500, 'Unable to generate PDF. Please try again later.');
        }
    }
}