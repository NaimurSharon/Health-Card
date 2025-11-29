<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\IdCard;
use App\Models\IdCardTemplate;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PrincipalIdCardController extends Controller
{
    public function index()
    {
        $school = auth()->user()->school;
        $students = Student::with(['user', 'class'])
            ->where('school_id', $school->id)
            ->where('status', 'active')
            ->get();
            
        $teachers = User::where('school_id', $school->id)
            ->where('role', 'teacher')
            ->where('status', 'active')
            ->get();
            
        return view('principal.id-cards.index', compact('students', 'teachers'));
    }
    
    public function templates()
    {
        $templates = IdCardTemplate::where('is_active', true)->get();
        return view('principal.id-cards.templates', compact('templates'));
    }
    
    public function generateStudentCard($studentId)
    {
        $student = Student::with(['user', 'class', 'section'])
            ->where('school_id', auth()->user()->school->id)
            ->findOrFail($studentId);
            
        $template = IdCardTemplate::where('type', 'student')
            ->where('is_active', true)
            ->first();
            
        if (!$template) {
            return redirect()->back()
                ->with('error', 'No active student ID card template found.');
        }
        
        // Generate QR code
        $qrData = [
            'type' => 'student',
            'id' => $student->id,
            'student_id' => $student->student_id,
            'name' => $student->user->name,
            'class' => $student->class->name,
        ];
        
        $qrCode = QrCode::size(100)->generate(json_encode($qrData));
        
        $pdf = Pdf::loadView('principal.id-cards.templates.student', [
            'student' => $student,
            'template' => $template,
            'qrCode' => $qrCode,
            'school' => auth()->user()->school,
        ]);
        
        return $pdf->download('id-card-' . $student->student_id . '.pdf');
    }
    
    public function generateTeacherCard($teacherId)
    {
        $teacher = User::where('school_id', auth()->user()->school->id)
            ->where('role', 'teacher')
            ->findOrFail($teacherId);
            
        $template = IdCardTemplate::where('type', 'teacher')
            ->where('is_active', true)
            ->first();
            
        if (!$template) {
            return redirect()->back()
                ->with('error', 'No active teacher ID card template found.');
        }
        
        // Generate QR code
        $qrData = [
            'type' => 'teacher',
            'id' => $teacher->id,
            'name' => $teacher->name,
            'specialization' => $teacher->specialization,
        ];
        
        $qrCode = QrCode::size(100)->generate(json_encode($qrData));
        
        $pdf = Pdf::loadView('principal.id-cards.templates.teacher', [
            'teacher' => $teacher,
            'template' => $template,
            'qrCode' => $qrCode,
            'school' => auth()->user()->school,
        ]);
        
        return $pdf->download('id-card-' . $teacher->id . '.pdf');
    }
    
    public function bulkGenerate(Request $request)
    {
        $request->validate([
            'type' => 'required|in:student,teacher',
            'ids' => 'required|array',
            'template_id' => 'required|exists:id_card_templates,id',
        ]);
        
        $template = IdCardTemplate::findOrFail($request->template_id);
        $school = auth()->user()->school;
        
        if ($request->type === 'student') {
            $students = Student::with(['user', 'class', 'section'])
                ->where('school_id', $school->id)
                ->whereIn('id', $request->ids)
                ->get();
                
            $pdf = Pdf::loadView('principal.id-cards.templates.bulk-student', [
                'students' => $students,
                'template' => $template,
                'school' => $school,
            ]);
            
            return $pdf->download('bulk-student-id-cards.pdf');
        } else {
            $teachers = User::where('school_id', $school->id)
                ->where('role', 'teacher')
                ->whereIn('id', $request->ids)
                ->get();
                
            $pdf = Pdf::loadView('principal.id-cards.templates.bulk-teacher', [
                'teachers' => $teachers,
                'template' => $template,
                'school' => $school,
            ]);
            
            return $pdf->download('bulk-teacher-id-cards.pdf');
        }
    }
}