<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VideoConsultation;
use App\Services\StreamVideoService;

class StudentConsultationController extends Controller
{
    protected $streamService;

    public function __construct(StreamVideoService $streamService)
    {
        $this->streamService = $streamService;
    }
    
    public function index()
    {
        $student = Auth::user();
        
        $consultations = VideoConsultation::where('user_id', $student->id)
            ->with(['doctor'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $upcomingConsultations = VideoConsultation::where('user_id', $student->id)
        ->whereIn('status', ['scheduled'])
        ->where('scheduled_for', '>=', now())
        ->with(['doctor'])
        ->get();

        return view('student.video-consultation.index', compact('consultations', 'upcomingConsultations'));
    }

    public function show($id)
    {
        $student = Auth::user();
        
        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $student->id)
            ->with(['doctor', 'payment'])
            ->firstOrFail();

        return view('student.video-consultation.show', compact('consultation'));
    }
    
    public function videoCall($id)
    {
        $student = Auth::user();
        
        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $student->id)
            ->where(function($query) {
                $query->where('status', 'scheduled')
                      ->orWhere('status', 'ongoing');
            })
            ->with(['doctor'])
            ->firstOrFail();

        // Update consultation status
        if ($consultation->status === 'scheduled') {
            $consultation->update([
                'status' => 'ongoing',
                'started_at' => now()
            ]);
        }

        $streamConfig = $this->streamService->getFrontendConfig(
            $student->id, 
            $student->name,
            $student->profile_photo_url ?? null
        );
        
        $streamConfig['callId'] = $consultation->call_id;

        return view('student.video-call-react', compact('consultation', 'streamConfig'));
    }

    // API Endpoints for React
    public function getCallConfig($id)
    {
        $student = Auth::user();
        
        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $student->id)
            ->with(['doctor'])
            ->firstOrFail();

        // Prevent API access to completed calls
        if ($consultation->status === 'completed') {
            return response()->json([
                'error' => 'This call has already ended and cannot be rejoined.',
                'status' => 'completed'
            ], 403);
        }

        $streamConfig = $this->streamService->getFrontendConfig(
            $student->id, 
            $student->name,
            $student->profile_photo_url ?? null
        );
        
        $streamConfig['callId'] = $consultation->call_id;

        return response()->json([
            'consultation' => $consultation,
            'streamConfig' => $streamConfig,
            'userType' => 'student'
        ]);
    }

    public function endCall(Request $request, $id)
    {
        $student = Auth::user();
        
        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $student->id)
            ->firstOrFail();

        // Get participant count before updating
        $meta = $consultation->call_metadata ?? [];
        $participants = isset($meta['participants']) && is_array($meta['participants']) ? $meta['participants'] : [];
        $participantCount = count($participants);

        // Log the end call event
        \Log::info("Student ending call for consultation {$id}. Current participants: {$participantCount}");

        // Always mark as completed when end call is initiated
        // This ensures no rejoin is possible, like Zoom/Google Meet
        $consultation->update([
            'status' => 'completed',
            'ended_at' => now(),
            'duration' => $request->duration ?? (($consultation->started_at) ? now()->diffInSeconds($consultation->started_at) : 0),
            'call_metadata' => array_merge($meta, ['ended_by' => 'student', 'ended_at_ts' => now()->timestamp])
        ]);

        // Broadcast the call ended event for real-time update
        try {
            event(new \App\Events\VideoCallEnded($consultation->call_id, 'student'));
        } catch (\Exception $e) {
            \Log::warning("Failed to broadcast VideoCallEnded event: " . $e->getMessage());
        }

        // Return full consultation data for display on show page
        return response()->json([
            'success' => true,
            'message' => 'Call ended successfully',
            'consultation' => $consultation->load(['student.user', 'doctor', 'appointment']),
            'redirect_url' => route('student.video-consultation.show', $id)
        ]);
    }

    /**
     * Redirects student to the React join page for a consultation.
     */
    public function joinCall($id)
    {
        $student = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('student_id', $student->id)
            ->where(function($query) {
                $query->where('status', 'scheduled')
                      ->orWhere('status', 'ongoing');
            })
            ->with(['doctor'])
            ->firstOrFail();

        // Update consultation status when student arrives
        if ($consultation->status === 'scheduled') {
            $consultation->update([
                'status' => 'ongoing',
                'started_at' => now()
            ]);
        }

        $streamConfig = $this->streamService->getFrontendConfig(
            $student->id,
            $student->name,
            $student->profile_photo_url ?? null
        );

        $streamConfig['callId'] = $consultation->call_id;

        return view('student.video-call-react', compact('consultation', 'streamConfig'));
    }

    /**
     * Called by the frontend when a participant joins the call.
     * Stores participant info into the consultation `call_metadata` JSON field.
     */
    public function participantJoined(Request $request, $id)
    {
        $student = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        $data = $request->validate([
            'participant.sessionId' => 'required|string',
            'participant.user.id' => 'required',
            'participant.user.name' => 'nullable|string',
            'participant.user.role' => 'nullable|string',
        ]);

        $participant = $data['participant'];

        $meta = $consultation->call_metadata ?? [];
        if (!isset($meta['participants']) || !is_array($meta['participants'])) {
            $meta['participants'] = [];
        }

        // ensure uniqueness by sessionId
        $existingIndex = null;
        foreach ($meta['participants'] as $idx => $p) {
            if (isset($p['sessionId']) && $p['sessionId'] === $participant['sessionId']) {
                $existingIndex = $idx;
                break;
            }
        }

        $participant['joined_at'] = now()->toISOString();

        if ($existingIndex === null) {
            $meta['participants'][] = $participant;
        } else {
            $meta['participants'][$existingIndex] = array_merge($meta['participants'][$existingIndex], $participant);
        }

        // save back to model
        $consultation->call_metadata = $meta;
        $consultation->save();

        // Broadcast participant joined
        try {
            event(new \App\Events\ParticipantJoined($consultation->call_id, $participant));
        } catch (\Exception $e) {
            // non-fatal
        }

        return response()->json([
            'success' => true,
            'participants' => $meta['participants'],
            'count' => count($meta['participants'])
        ]);
    }

    /**
     * Return the current participants list for a consultation.
     */
    public function getParticipants($id)
    {
        $student = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        $meta = $consultation->call_metadata ?? [];
        $participants = isset($meta['participants']) && is_array($meta['participants']) ? $meta['participants'] : [];

        // Filter stale participants based on TTL (seconds). Default 30s.
        $ttl = config('video.cleanup_ttl', 30);
        $now = \Carbon\Carbon::now();
        $kept = [];

        foreach ($participants as $p) {
            $timestamp = null;
            if (!empty($p['last_seen'])) {
                $timestamp = \Carbon\Carbon::parse($p['last_seen']);
            } elseif (!empty($p['joined_at'])) {
                $timestamp = \Carbon\Carbon::parse($p['joined_at']);
            }

            if ($timestamp === null) {
                $kept[] = $p;
                continue;
            }

            if ($now->diffInSeconds($timestamp) <= $ttl) {
                $kept[] = $p;
            }
        }

        // If some participants were removed, persist the cleaned list
        if (count($kept) !== count($participants)) {
            $meta['participants'] = array_values($kept);
            $consultation->call_metadata = $meta;
            $consultation->save();
        }

        return response()->json([
            'success' => true,
            'participants' => $kept,
            'count' => count($kept)
        ]);
    }

    /**
     * Called by frontend when a participant leaves the call.
     */
    public function participantLeft(Request $request, $id)
    {
        $student = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        $data = $request->validate([
            'participant.sessionId' => 'required|string'
        ]);

        $sessionId = $data['participant']['sessionId'];

        $meta = $consultation->call_metadata ?? [];
        $participants = isset($meta['participants']) && is_array($meta['participants']) ? $meta['participants'] : [];

        $participants = array_values(array_filter($participants, function ($p) use ($sessionId) {
            return !isset($p['sessionId']) || $p['sessionId'] !== $sessionId;
        }));

        $meta['participants'] = $participants;
        $consultation->call_metadata = $meta;
        $consultation->save();

        // Broadcast participant left
        try {
            event(new \App\Events\ParticipantLeft($consultation->call_id, ['sessionId' => $sessionId]));
        } catch (\Exception $e) {
            // ignore
        }

        return response()->json([
            'success' => true,
            'participants' => $participants,
            'count' => count($participants)
        ]);
    }

    /**
     * Heartbeat endpoint to mark participant as active (update last_seen)
     */
    public function heartbeat(Request $request, $id)
    {
        $student = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $student->id)
            ->firstOrFail();

        $data = $request->validate([
            'participant.sessionId' => 'required|string',
            'participant.user.id' => 'nullable',
        ]);

        $sessionId = $data['participant']['sessionId'];

        $meta = $consultation->call_metadata ?? [];
        
        // Update patient heartbeat
        $meta['patient_last_heartbeat'] = now()->toISOString();
        
        // If call is active and patient was marked as disconnected, clear it
        if ($consultation->status === 'ongoing' && isset($meta['patient_disconnect_at'])) {
            unset($meta['patient_disconnect_at']);
        }
        
        // Track participants for session management
        if (!isset($meta['participants']) || !is_array($meta['participants'])) {
            $meta['participants'] = [];
        }

        $found = false;
        foreach ($meta['participants'] as $idx => $p) {
            if (isset($p['sessionId']) && $p['sessionId'] === $sessionId) {
                $meta['participants'][$idx]['last_seen'] = now()->toISOString();
                $found = true;
                break;
            }
        }

        if (!$found) {
            $meta['participants'][] = [
                'sessionId' => $sessionId,
                'user' => $data['participant']['user'] ?? null,
                'role' => 'patient',
                'joined_at' => now()->toISOString(),
                'last_seen' => now()->toISOString(),
            ];
        }

        $consultation->call_metadata = $meta;
        $consultation->save();

        return response()->json([
            'success' => true,
            'participants' => $meta['participants'],
            'count' => count($meta['participants']),
            'call_status' => $consultation->status
        ]);
    }

    /**
     * Check presence of both participants in waiting room
     * Returns current status of both participants and call state
     */
    public function checkPresence($id)
    {
        $student = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $student->id)
            ->firstOrFail();

        $meta = $consultation->call_metadata ?? [];
        
        $now = \Carbon\Carbon::now();
        $patientReady = false;
        $doctorReady = false;
        $callActive = $consultation->status === 'ongoing';
        
        // Check patient presence (heartbeat within last 10 seconds)
        if (isset($meta['patient_ready']) && $meta['patient_ready']) {
            if (isset($meta['patient_last_heartbeat'])) {
                $lastHeartbeat = \Carbon\Carbon::parse($meta['patient_last_heartbeat']);
                $patientReady = $now->diffInSeconds($lastHeartbeat) <= 10;
            } else if (isset($meta['patient_ready_at'])) {
                $readyAt = \Carbon\Carbon::parse($meta['patient_ready_at']);
                $patientReady = $now->diffInSeconds($readyAt) <= 10;
            }
        }
        
        // Check doctor presence (heartbeat within last 10 seconds)
        if (isset($meta['doctor_ready']) && $meta['doctor_ready']) {
            if (isset($meta['doctor_last_heartbeat'])) {
                $lastHeartbeat = \Carbon\Carbon::parse($meta['doctor_last_heartbeat']);
                $doctorReady = $now->diffInSeconds($lastHeartbeat) <= 10;
            } else if (isset($meta['doctor_ready_at'])) {
                $readyAt = \Carbon\Carbon::parse($meta['doctor_ready_at']);
                $doctorReady = $now->diffInSeconds($readyAt) <= 10;
            }
        }
        
        // Check if someone disconnected during active call
        $disconnectInfo = null;
        if ($callActive && isset($meta['call_started_at'])) {
            if (!$patientReady && isset($meta['patient_disconnect_at'])) {
                $disconnectAt = \Carbon\Carbon::parse($meta['patient_disconnect_at']);
                $secondsDisconnected = $now->diffInSeconds($disconnectAt);
                if ($secondsDisconnected >= 300) { // 5 minutes
                    // Auto-end call
                    $consultation->update([
                        'status' => 'completed',
                        'ended_at' => now(),
                        'end_reason' => 'Patient disconnected for 5 minutes'
                    ]);
                    $callActive = false;
                } else {
                    $disconnectInfo = [
                        'who' => 'patient',
                        'seconds_remaining' => 300 - $secondsDisconnected
                    ];
                }
            } else if (!$doctorReady && isset($meta['doctor_disconnect_at'])) {
                $disconnectAt = \Carbon\Carbon::parse($meta['doctor_disconnect_at']);
                $secondsDisconnected = $now->diffInSeconds($disconnectAt);
                if ($secondsDisconnected >= 300) { // 5 minutes
                    // Auto-end call
                    $consultation->update([
                        'status' => 'completed',
                        'ended_at' => now(),
                        'end_reason' => 'Doctor disconnected for 5 minutes'
                    ]);
                    $callActive = false;
                } else {
                    $disconnectInfo = [
                        'who' => 'doctor',
                        'seconds_remaining' => 300 - $secondsDisconnected
                    ];
                }
            }
        }
        
        $bothReady = $patientReady && $doctorReady;
        
        return response()->json([
            'success' => true,
            'patient_present' => $patientReady,
            'doctor_present' => $doctorReady,
            'both_ready' => $bothReady,
            'call_active' => $callActive,
            'call_status' => $consultation->status,
            'disconnect_info' => $disconnectInfo,
            'can_start_call' => $bothReady && !$callActive
        ]);
    }

    /**
     * Mark patient as ready in waiting room
     */
    public function markReady($id)
    {
        $student = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $student->id)
            ->firstOrFail();

        $meta = $consultation->call_metadata ?? [];
        
        // Mark patient as ready
        $meta['patient_ready'] = true;
        $meta['patient_ready_at'] = now()->toISOString();
        $meta['patient_last_heartbeat'] = now()->toISOString();
        
        // Clear disconnect timestamp if reconnecting
        if (isset($meta['patient_disconnect_at'])) {
            unset($meta['patient_disconnect_at']);
        }

        $consultation->call_metadata = $meta;
        $consultation->save();

        $doctorReady = $meta['doctor_ready'] ?? false;
        $bothReady = $meta['patient_ready'] && $doctorReady;
        
        // Only start call if both are ready AND call hasn't started yet
        if ($bothReady && $consultation->status === 'scheduled') {
            $meta['call_started_at'] = now()->toISOString();
            $consultation->call_metadata = $meta;
            $consultation->update([
                'status' => 'ongoing',
                'started_at' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'patient_ready' => true,
            'doctor_ready' => $doctorReady,
            'both_ready' => $bothReady,
            'call_active' => $consultation->status === 'ongoing',
            'can_start_call' => $bothReady
        ]);
    }
}