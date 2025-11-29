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
        $healthCard = HealthCard::where('student_id', $student->student->id)
            ->where('status', 'active')
            ->first();

        // Get recent medical records for health card
        $medicalRecords = MedicalRecord::where('student_id', $student->student->id)
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
        $healthCard = HealthCard::where('student_id', $student->student->id)
            ->where('status', 'active')
            ->first();

        // Get recent medical records for health card
        $medicalRecords = MedicalRecord::where('student_id', $student->student->id)
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
            ->with(['template', 'student.user'])
            ->firstOrFail();

        return view('student.id-card.print', compact('idCard'));
    }

    public function downloadHealthCard()
    {
        $student = Auth::user();
        
        $healthCard = HealthCard::where('student_id', $student->student->id)
            ->where('status', 'active')
            ->firstOrFail();

        $medicalRecords = MedicalRecord::where('student_id', $student->student->id)
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