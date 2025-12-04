<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\IdCard;
use App\Models\HealthCard;
use App\Models\MedicalRecord;

class StudentIdCardController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        
        // Get student's ID cards
        $idCards = IdCard::where('student_id', $student->student->id)
            ->with(['template'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get student's health card
        $healthCard = HealthCard::where('user_id', $student->id)
            ->where('status', 'active')
            ->first();

        // Get recent medical records for health card
        $medicalRecords = MedicalRecord::where('user_id', $student->id)
            ->orderBy('record_date', 'desc')
            ->take(5)
            ->get();

        return view('student.id-card.index', compact('idCards', 'healthCard', 'medicalRecords'));
    }
    
    public function myIdCards()
    {
        $student = Auth::user();
        
        // Get student's ID cards
        $idCards = IdCard::where('student_id', $student->student->id)
            ->with(['template'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get student's health card
        $healthCard = HealthCard::where('user_id', $student->id)
            ->where('status', 'active')
            ->first();

        // Get recent medical records for health card
        $medicalRecords = MedicalRecord::where('user_id', $student->id)
            ->orderBy('record_date', 'desc')
            ->take(5)
            ->get();

        return view('student.id-card.index', compact('idCards', 'healthCard', 'medicalRecords'));
    }

    public function downloadIdCard($id)
    {
        $student = Auth::user();
        
        $idCard = IdCard::where('id', $id)
            ->where('student_id', $student->student->id)
            ->with(['template', 'student.user.school', 'student.class', 'student.section'])
            ->firstOrFail();

        // Render the view to HTML
        $html = view('student.id-card.pdf', compact('idCard'))->render();

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
        $fileName = 'id-card-' . $idCard->card_number . '-' . now()->format('Y-m-d') . '.pdf';
        
        return response()->streamDownload(function() use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function downloadHealthCard()
    {
        $student = Auth::user();
        
        $healthCard = HealthCard::where('user_id', $student->id)
            ->where('status', 'active')
            ->firstOrFail();

        $medicalRecords = MedicalRecord::where('user_id', $student->id)
            ->orderBy('record_date', 'desc')
            ->take(10)
            ->get();

        return view('student.id-card.health-card-print', compact('healthCard', 'medicalRecords'));
    }

    public function verifyQrCode($cardNumber)
    {
        $idCard = IdCard::where('card_number', $cardNumber)
            ->with(['student.user', 'user'])
            ->firstOrFail();

        return view('id-card.verify', compact('idCard'));
    }

    public function download()
    {
        $student = Auth::user();
        
        $idCard = \App\Models\IdCard::where('student_id', $student->id)
            ->where('status', 'active')
            ->with(['template'])
            ->firstOrFail();

        // Generate PDF or return view for download
        return view('student.id-card.download', compact('idCard'));
    }
}