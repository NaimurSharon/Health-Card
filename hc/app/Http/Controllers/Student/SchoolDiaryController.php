<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SchoolDiaryController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user();
        $studentDetails = $student->student;
        
        if (!$studentDetails) {
            return view('student.school-diary.index', [
                'homeworks' => collect(),
                'todaysHomeworks' => collect(),
                'filterDate' => $request->get('date', today()->format('Y-m-d')),
                'studentDetails' => null,
                'error' => 'Student details not found. Please contact administration.'
            ]);
        }

        try {
            // Get filter date from request or default to today
            $filterDate = $request->get('date', today()->format('Y-m-d'));
            
            // Get today's homeworks for the table
            $todaysHomeworks = \App\Models\ClassDiary::where('class_id', $studentDetails->class_id)
                ->where('section_id', $studentDetails->section_id)
                ->where('entry_date', $filterDate)
                ->with(['teacher', 'subject'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Get recent homeworks for the sidebar (last 7 days)
            $recentHomeworks = \App\Models\ClassDiary::where('class_id', $studentDetails->class_id)
                ->where('section_id', $studentDetails->section_id)
                ->where('entry_date', '>=', now()->subDays(7))
                ->with(['teacher', 'subject'])
                ->orderBy('entry_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('entry_date');

            // Get available dates for the date filter
            $availableDates = \App\Models\ClassDiary::where('class_id', $studentDetails->class_id)
                ->where('section_id', $studentDetails->section_id)
                ->distinct()
                ->orderBy('entry_date', 'desc')
                ->pluck('entry_date');

            return view('student.school-diary.index', compact(
                'todaysHomeworks',
                'recentHomeworks',
                'filterDate',
                'availableDates',
                'studentDetails'
            ));

        } catch (\Exception $e) {
            return view('student.school-diary.index', [
                'todaysHomeworks' => collect(),
                'recentHomeworks' => collect(),
                'filterDate' => $request->get('date', today()->format('Y-m-d')),
                'availableDates' => collect(),
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

    // Download homework details as PDF
    public function downloadHomeworkPdf($id)
    {
        $student = Auth::user();
        $studentDetails = $student->student;
        
        if (!$studentDetails) {
            return redirect()->route('student.school-diary')
                ->with('error', 'Student details not found. Please contact administration.');
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

            // Check if dompdf is properly installed
            if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
                throw new \Exception('PDF generation library not found');
            }

            $pdf = Pdf::loadView('student.school-diary.homework-pdf', $data);
            
            $filename = 'homework-details-' . $homework->id . '-' . ($studentDetails->student_id ?? 'student') . '.pdf';
            
            return $pdf->download($filename);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('student.school-diary')
                ->with('error', 'Homework not found or you do not have access to view it.');
        } catch (\Exception $e) {
            \Log::error('Download homework PDF error: ' . $e->getMessage());
            return redirect()->route('student.school-diary')
                ->with('error', 'Unable to generate PDF. Please try again later. Error: ' . $e->getMessage());
        }
    }
    
    public function downloadPdf(Request $request)
    {
        $student = Auth::user();
        $studentDetails = $student->student;
        
        if (!$studentDetails) {
            return redirect()->route('student.school-diary')
                ->with('error', 'Student details not found. Please contact administration.');
        }

        try {
            $filterDate = $request->get('date', today()->format('Y-m-d'));
            
            $homeworks = \App\Models\ClassDiary::where('class_id', $studentDetails->class_id)
                ->where('section_id', $studentDetails->section_id)
                ->where('entry_date', $filterDate)
                ->with(['teacher', 'subject'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Generate HTML content
            $html = $this->generatePdfHtml($homeworks, $student, $studentDetails, $filterDate);
            
            $filename = 'homework-' . $filterDate . '-' . ($studentDetails->student_id ?? 'student') . '.html';
            
            // For now, return HTML file. You can later convert this to PDF
            return response($html)
                ->header('Content-Type', 'text/html')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            \Log::error('Download PDF error: ' . $e->getMessage());
            return redirect()->route('student.school-diary')
                ->with('error', 'Unable to generate document. Please try again later.');
        }
    }

    private function generatePdfHtml($homeworks, $student, $studentDetails, $filterDate)
    {
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <title>Homework - ' . $filterDate . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #06AC73; padding-bottom: 20px; }
                .school-name { font-size: 24px; font-weight: bold; color: #06AC73; margin-bottom: 5px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th { background-color: #06AC73; color: white; padding: 12px; text-align: left; }
                td { padding: 12px; border-bottom: 1px solid #ddd; }
                .footer { text-align: center; margin-top: 40px; font-size: 12px; color: #999; }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="school-name">' . ($student->school->name ?? 'School') . '</div>
                <div>Homework for ' . \Carbon\Carbon::parse($filterDate)->format('F j, Y') . '</div>
                <div>Student: ' . $student->name . ' | Class: ' . ($studentDetails->class->name ?? 'N/A') . ' | Section: ' . ($studentDetails->section->name ?? 'N/A') . '</div>
                <div>Generated on: ' . now()->format('F j, Y g:i A') . '</div>
            </div>';

        if ($homeworks->count() > 0) {
            $html .= '<table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Subject</th>
                        <th>Homework</th>
                        <th>Teacher</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($homeworks as $index => $homework) {
                $html .= '<tr>
                    <td>' . ($index + 1) . '</td>
                    <td><strong>' . ($homework->subject->name ?? 'General') . '</strong></td>
                    <td>
                        <div><strong>' . $homework->homework_title . '</strong></div>
                        <div style="color: #666;">' . $homework->homework_description . '</div>
                    </td>
                    <td>' . ($homework->teacher->name ?? 'Teacher') . '</td>
                    <td>' . ($homework->due_date ? $homework->due_date->format('M j, Y') : 'No due date') . '</td>
                </tr>';
            }

            $html .= '</tbody></table>';
        } else {
            $html .= '<div style="text-align: center; padding: 40px; color: #666;">
                <h3>No homework assigned for ' . \Carbon\Carbon::parse($filterDate)->format('F j, Y') . '</h3>
            </div>';
        }

        $html .= '<div class="footer">
            Generated by ' . ($student->school->name ?? 'School') . ' Student Portal
        </div>
        </body>
        </html>';

        return $html;
    }
}