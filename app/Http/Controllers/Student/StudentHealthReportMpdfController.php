<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

class StudentHealthReportMpdfController extends Controller
{
    /**
     * Download health report as PDF using mPDF
     */
    public function downloadPdf()
    {
        $student = Auth::user();
        $studentDetails = $student->student;
        
        if (!$studentDetails) {
            abort(404, 'Student details not found');
        }

        $healthReport = \App\Models\StudentHealthReport::where('student_id', $studentDetails->id)
            ->with(['reportData.field.category'])
            ->first();

        $categories = \App\Models\HealthReportCategory::with(['fields'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $school = $student->school;
        $studentDetails->load(['class', 'section']);
        $class = $studentDetails->class;
        $section = $studentDetails->section;
        
        $annualRecords = \App\Models\AnnualHealthRecord::where('student_id', $studentDetails->id)
            ->latestFirst()
            ->get();
            
        $activeHealthCard = \App\Models\HealthCard::where('student_id', $studentDetails->id)
            ->where('status', 'active')
            ->where('expiry_date', '>=', now())
            ->first();

        // Render the view to HTML
        $html = view('student.health-report.pdf', compact(
            'healthReport',
            'studentDetails',
            'categories',
            'annualRecords',
            'school',
            'student',
            'class',
            'section',
            'activeHealthCard'
        ))->render();

        // Create mPDF instance with Bengali font support
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9,
            'default_font' => 'dejavusans',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        // Add Bengali fonts manually
        $fontDir = storage_path('fonts/');
        
        // Add NotoSansBengali font
        $notoSansPath = $fontDir . 'NotoSansBengali.ttf';
        if (file_exists($notoSansPath)) {
            $mpdf->AddFont('NotoSansBengali', '', 'NotoSansBengali.ttf', $fontDir);
            $mpdf->AddFont('NotoSansBengali', 'B', 'NotoSansBengali.ttf', $fontDir);
        }
        
        // Add Nikosh font
        $nikoshPath = $fontDir . 'Nikosh.ttf';
        if (file_exists($nikoshPath)) {
            $mpdf->AddFont('Nikosh', '', 'Nikosh.ttf', $fontDir);
            $mpdf->AddFont('Nikosh', 'B', 'Nikosh.ttf', $fontDir);
        }

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Output PDF
        $fileName = 'health-report-' . $studentDetails->id . '-' . now()->format('Y-m-d') . '.pdf';
        
        return response()->streamDownload(function() use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}