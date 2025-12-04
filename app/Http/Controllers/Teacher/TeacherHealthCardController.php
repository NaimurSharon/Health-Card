<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\HealthCard;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherHealthCardController extends Controller
{
    /**
     * Display teacher's health card
     */
    public function index()
    {
        $teacher = Auth::user();
        
        // Get teacher's health card
        $healthCard = HealthCard::where('user_id', $teacher->id)
            ->where('status', 'active')
            ->first();

        // Get recent medical records
        $medicalRecords = MedicalRecord::where('user_id', $teacher->id)
            ->orderBy('record_date', 'desc')
            ->take(10)
            ->get();

        return view('teacher.health-card.index', compact('healthCard', 'medicalRecords'));
    }

    /**
     * Download health card as PDF
     */
    public function downloadPdf()
    {
        $teacher = Auth::user();
        
        $healthCard = HealthCard::where('user_id', $teacher->id)
            ->where('status', 'active')
            ->firstOrFail();

        $medicalRecords = MedicalRecord::where('user_id', $teacher->id)
            ->orderBy('record_date', 'desc')
            ->take(10)
            ->get();

        // Render the view to HTML
        $html = view('teacher.health-card.pdf', compact('healthCard', 'medicalRecords', 'teacher'))->render();

        // Create mPDF instance with Bengali font support
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        
        // Ensure temp directory exists
        $tempDir = storage_path('app/mpdf');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'tempDir' => $tempDir,
            'fontDir' => array_merge($fontDirs, [storage_path('fonts')]),
            'fontdata' => $fontData + [
                'notoSansBengali' => [
                    'R' => 'NotoSansBengali.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font' => 'notoSansBengali',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);
        
        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Output PDF
        $fileName = 'health-card-' . $healthCard->card_number . '-' . now()->format('Y-m-d') . '.pdf';
        
        return response()->streamDownload(function() use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Print health card
     */
    public function print()
    {
        $teacher = Auth::user();
        
        $healthCard = HealthCard::where('user_id', $teacher->id)
            ->where('status', 'active')
            ->firstOrFail();

        $medicalRecords = MedicalRecord::where('user_id', $teacher->id)
            ->orderBy('record_date', 'desc')
            ->take(10)
            ->get();

        return view('teacher.health-card.print', compact('healthCard', 'medicalRecords', 'teacher'));
    }
}
