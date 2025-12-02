<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\VideoConsultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DoctorCallController extends Controller
{
    /**
     * Accept an incoming call
     * This marks the doctor as ready and redirects to the video call page
     */
    public function acceptCall(Request $request)
    {
        $doctor = Auth::user();
        
        $request->validate([
            'call_id' => 'required|exists:video_consultations,id'
        ]);

        $consultation = VideoConsultation::where('id', $request->call_id)
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();

        // Update call metadata to mark doctor as ready
        $metadata = $consultation->call_metadata ?? [];
        $metadata['doctor_ready'] = true;
        $metadata['doctor_ready_at'] = now()->toISOString();
        $metadata['doctor_last_heartbeat'] = now()->toISOString();
        
        // Clear any disconnect timestamp
        unset($metadata['doctor_disconnect_at']);

        // Check if patient is also ready
        $patientReady = $metadata['patient_ready'] ?? false;
        
        // If both are ready and call hasn't started, start it
        if ($patientReady && !isset($metadata['call_started_at'])) {
            $consultation->update([
                'status' => 'ongoing',
                'started_at' => now(),
                'call_metadata' => array_merge($metadata, [
                    'call_started_at' => now()->toISOString()
                ])
            ]);
        } else {
            // Just update metadata
            $consultation->update([
                'call_metadata' => $metadata
            ]);
        }

        return response()->json([
            'success' => true,
            'redirect_url' => route('doctor.video-consultation.join', $consultation->id)
        ]);
    }

    /**
     * Reject an incoming call
     */
    public function rejectCall(Request $request)
    {
        $doctor = Auth::user();
        
        $request->validate([
            'call_id' => 'required|exists:video_consultations,id'
        ]);

        $consultation = VideoConsultation::where('id', $request->call_id)
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();

        // Update metadata to record rejection
        $metadata = $consultation->call_metadata ?? [];
        $metadata['doctor_rejected_at'] = now()->toISOString();
        $metadata['rejection_reason'] = 'Doctor declined the call';

        $consultation->update([
            'status' => 'cancelled',
            'ended_at' => now(),
            'call_metadata' => $metadata
        ]);

        Log::info("Doctor {$doctor->id} rejected call {$consultation->id}");

        return response()->json(['success' => true]);
    }

    /**
     * Auto-reject a call when the 30-second timer expires
     */
    public function autoRejectCall(Request $request)
    {
        $doctor = Auth::user();
        
        $request->validate([
            'call_id' => 'required|exists:video_consultations,id'
        ]);

        $consultation = VideoConsultation::where('id', $request->call_id)
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();

        // Update metadata to record auto-rejection
        $metadata = $consultation->call_metadata ?? [];
        $metadata['auto_rejected_at'] = now()->toISOString();
        $metadata['rejection_reason'] = 'Doctor did not respond within 30 seconds';

        $consultation->update([
            'status' => 'missed',
            'ended_at' => now(),
            'call_metadata' => $metadata
        ]);

        Log::info("Call {$consultation->id} auto-rejected due to timeout");

        return response()->json(['success' => true]);
    }

    /**
     * Get pending calls for the doctor
     * Returns calls that are:
     * - Status: scheduled or pending
     * - Created/scheduled within the last 5 minutes
     * - Not yet accepted or rejected
     */
    public function getPendingCalls(Request $request)
    {
        $doctor = Auth::user();
        
        // Look for calls that are waiting for doctor to accept
        $pendingCall = VideoConsultation::where('doctor_id', $doctor->id)
        ->where('type', 'instant')
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->where(function($query) {
                // Either created recently (instant calls)
                $query->where('created_at', '>=', now()->subMinutes(5))
                    // Or scheduled for now/soon
                    ->orWhere(function($q) {
                        $q->where('scheduled_for', '<=', now()->addMinutes(5))
                          ->where('scheduled_for', '>=', now()->subMinutes(5));
                    });
            })
            ->with(['user', 'student.user', 'student.class'])
            ->orderBy('created_at', 'desc')
            ->first();

        if ($pendingCall) {
            // Get patient name - handle both user_id and student relationship
            $patientName = 'Patient';
            if ($pendingCall->user) {
                $patientName = $pendingCall->user->name;
            } elseif ($pendingCall->student && $pendingCall->student->user) {
                $patientName = $pendingCall->student->user->name;
            }

            return response()->json([
                'hasCall' => true,
                'call' => [
                    'id' => $pendingCall->id,
                    'call_id' => $pendingCall->call_id,
                    'user_id' => $pendingCall->user_id,
                    'patient_type' => $pendingCall->patient_type ?? 'student',
                    'doctor_id' => $pendingCall->doctor_id,
                    'student_name' => $patientName,
                    'student_class' => $pendingCall->student->class->name ?? 'N/A',
                    'symptoms' => $pendingCall->symptoms ?? 'General consultation',
                    'type' => $pendingCall->type ?? 'Video Call',
                    'fee' => $pendingCall->consultation_fee ?? 0,
                    'created_at' => $pendingCall->created_at->toISOString(),
                    'scheduled_for' => $pendingCall->scheduled_for ? $pendingCall->scheduled_for->toISOString() : null,
                ]
            ]);
        }

        return response()->json(['hasCall' => false]);
    }
}