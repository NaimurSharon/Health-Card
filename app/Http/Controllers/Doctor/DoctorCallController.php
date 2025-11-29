<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\VideoConsultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorCallController extends Controller
{
    public function acceptCall(Request $request)
    {
        $doctor = Auth::user();
        
        $request->validate([
            'call_id' => 'required|exists:video_consultations,id'
        ]);

        $consultation = VideoConsultation::where('id', $request->call_id)
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();

        $consultation->update([
            'status' => 'ongoing',
            'started_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'redirect_url' => route('doctor.video-consultation.join', $consultation->id)
        ]);
    }

    public function rejectCall(Request $request)
    {
        $doctor = Auth::user();
        
        $request->validate([
            'call_id' => 'required|exists:video_consultations,id'
        ]);
        

        $consultation = VideoConsultation::where('id', $request->call_id)
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();

        $consultation->update([
            'status' => 'cancelled',
            'ended_at' => now()
        ]);

        return response()->json(['success' => true]);
    }

    public function autoRejectCall(Request $request)
    {
        $doctor = Auth::user();
        
        $request->validate([
            'call_id' => 'required|exists:video_consultations,id'
        ]);

        $consultation = VideoConsultation::where('id', $request->call_id)
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();

        // $consultation->update([
        //     'status' => 'missed',
        //     'ended_at' => now()
        // ]);

        return response()->json(['success' => true]);
    }

    public function getPendingCalls(Request $request)
    {
        $doctor = Auth::user();
        
        $pendingCall = VideoConsultation::where('doctor_id', $doctor->id)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->where('status', '>=', 'ongoing')
            ->with(['student.user', 'student.class'])
            ->first();

        if ($pendingCall) {
            return response()->json([
                'hasCall' => true,
                'call' => [
                    'id' => $pendingCall->id,
                    'call_id' => $pendingCall->call_id,
                    'student_id' => $pendingCall->student_id,
                    'doctor_id' => $pendingCall->doctor_id,
                    'student_name' => $pendingCall->student->user->name ?? 'Student',
                    'student_class' => $pendingCall->student->class->name ?? 'N/A',
                    'symptoms' => $pendingCall->symptoms,
                    'type' => $pendingCall->type,
                    'fee' => $pendingCall->consultation_fee,
                    'created_at' => $pendingCall->created_at->toISOString(),
                ]
            ]);
        }

        return response()->json(['hasCall' => false]);
    }
}