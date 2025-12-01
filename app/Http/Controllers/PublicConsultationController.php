<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VideoConsultation;
use App\Services\StreamVideoService;

class PublicConsultationController extends Controller
{
    protected $streamService;

    public function __construct(StreamVideoService $streamService)
    {
        $this->streamService = $streamService;
    }
    
    public function index()
    {
        $user = Auth::user();
        
        $consultations = VideoConsultation::where('user_id', $user->id)
            ->with(['doctor', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $upcomingConsultations = VideoConsultation::where('user_id', $user->id)
        ->whereIn('status', ['scheduled'])
        ->where('scheduled_for', '>=', now())
        ->with(['doctor', 'user'])
        ->get();

        return view('frontend.video-consultation.index', compact('consultations', 'upcomingConsultations'));
    }

    public function show($id)
    {
        $user = Auth::user();
        
        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['doctor', 'user', 'payment'])
            ->firstOrFail();

        return view('frontend.video-consultation.show', compact('consultation'));
    }
    
    public function videoCall($id)
    {
        $user = Auth::user();
        
        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $user->id)
            ->where(function($query) {
                $query->where('status', 'scheduled')
                      ->orWhere('status', 'ongoing');
            })
            ->with(['doctor', 'user'])
            ->firstOrFail();

        // Update consultation status
        if ($consultation->status === 'scheduled') {
            $consultation->update([
                'status' => 'ongoing',
                'started_at' => now()
            ]);
        }

        $streamConfig = $this->streamService->getFrontendConfig(
            $user->id, 
            $user->name,
            $user->profile_photo_url ?? null
        );
        
        $streamConfig['callId'] = $consultation->call_id;

        return view('frontend.video-call-react', compact('consultation', 'streamConfig'));
    }

    // API Endpoints for React
    public function getCallConfig($id)
    {
        $user = Auth::user();
        
        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['doctor', 'user'])
            ->firstOrFail();

        // Prevent API access to completed calls
        if ($consultation->status === 'completed') {
            return response()->json([
                'error' => 'This call has already ended and cannot be rejoined.',
                'status' => 'completed'
            ], 403);
        }

        $streamConfig = $this->streamService->getFrontendConfig(
            $user->id, 
            $user->name,
            $user->profile_photo_url ?? null
        );
        
        $streamConfig['callId'] = $consultation->call_id;

        // Determine user type based on role
        $userType = in_array($user->role, ['student', 'teacher', 'principal', 'public']) 
            ? 'patient' 
            : 'user';

        return response()->json([
            'consultation' => $consultation,
            'streamConfig' => $streamConfig,
            'userType' => $userType
        ]);
    }

    public function endCall(Request $request, $id)
    {
        $user = Auth::user();
        
        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Get participant count before updating
        $meta = $consultation->call_metadata ?? [];
        $participants = isset($meta['participants']) && is_array($meta['participants']) ? $meta['participants'] : [];
        $participantCount = count($participants);

        // Always mark as completed when end call is initiated
        // This ensures no rejoin is possible, like Zoom/Google Meet
        $consultation->update([
            'status' => 'completed',
            'ended_at' => now(),
            'duration' => $request->duration ?? (($consultation->started_at) ? now()->diffInSeconds($consultation->started_at) : 0),
            'call_metadata' => array_merge($meta, ['ended_by' => 'patient', 'ended_at_ts' => now()->timestamp])
        ]);

        // Broadcast the call ended event for real-time update
        try {
            event(new \App\Events\VideoCallEnded($consultation->call_id, 'patient'));
        } catch (\Exception $e) {
            \Log::warning("Failed to broadcast VideoCallEnded event: " . $e->getMessage());
        }

        // Return full consultation data for display on show page
        return response()->json([
            'success' => true,
            'message' => 'Call ended successfully',
            'consultation' => $consultation->load(['user', 'doctor', 'appointment']),
            'redirect_url' => route('video-consultation.show', $id)
        ]);
    }

    /**
     * Redirects user to the React join page for a consultation.
     */
    public function joinCall($id)
    {
        $user = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $user->id)
            ->where(function($query) {
                $query->where('status', 'scheduled')
                      ->orWhere('status', 'ongoing');
            })
            ->with(['doctor', 'user'])
            ->firstOrFail();

        // Update consultation status when user arrives
        if ($consultation->status === 'scheduled') {
            $consultation->update([
                'status' => 'ongoing',
                'started_at' => now()
            ]);
        }

        $streamConfig = $this->streamService->getFrontendConfig(
            $user->id,
            $user->name,
            $user->profile_photo_url ?? null
        );

        $streamConfig['callId'] = $consultation->call_id;

        return view('frontend.video-call-react', compact('consultation', 'streamConfig'));
    }

    /**
     * Called by the frontend when a participant joins the call.
     * Stores participant info into the consultation `call_metadata` JSON field.
     */
    public function participantJoined(Request $request, $id)
    {
        $user = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $user->id)
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
        $user = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $user->id)
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
        $user = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $user->id)
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
        $user = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $data = $request->validate([
            'participant.sessionId' => 'required|string',
            'participant.user.id' => 'nullable',
        ]);

        $sessionId = $data['participant']['sessionId'];

        $meta = $consultation->call_metadata ?? [];
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
            // Add a minimal participant record so cleanup/participants see it
            $meta['participants'][] = [
                'sessionId' => $sessionId,
                'user' => $data['participant']['user'] ?? null,
                'joined_at' => now()->toISOString(),
                'last_seen' => now()->toISOString(),
            ];
        }

        $consultation->call_metadata = $meta;
        $consultation->save();

        return response()->json([
            'success' => true,
            'participants' => $meta['participants'],
            'count' => count($meta['participants'])
        ]);
    }

    /**
     * Check presence of both participants in waiting room
     */
    public function checkPresence($id)
    {
        $user = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $meta = $consultation->call_metadata ?? [];
        $patientReady = $meta['patient_ready'] ?? false;
        $doctorReady = $meta['doctor_ready'] ?? false;

        // Check timestamps to ensure they're recent (within last 10 seconds)
        $now = \Carbon\Carbon::now();
        if (isset($meta['patient_ready_at'])) {
            $patientReadyAt = \Carbon\Carbon::parse($meta['patient_ready_at']);
            if ($now->diffInSeconds($patientReadyAt) > 10) {
                $patientReady = false;
            }
        }
        if (isset($meta['doctor_ready_at'])) {
            $doctorReadyAt = \Carbon\Carbon::parse($meta['doctor_ready_at']);
            if ($now->diffInSeconds($doctorReadyAt) > 10) {
                $doctorReady = false;
            }
        }

        return response()->json([
            'success' => true,
            'patient_present' => $patientReady,
            'doctor_present' => $doctorReady,
            'both_ready' => $patientReady && $doctorReady
        ]);
    }

    /**
     * Mark patient as ready in waiting room
     */
    public function markReady($id)
    {
        $user = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $meta = $consultation->call_metadata ?? [];
        $meta['patient_ready'] = true;
        $meta['patient_ready_at'] = now()->toISOString();

        $consultation->call_metadata = $meta;
        $consultation->save();

        // Check if both are ready
        $bothReady = ($meta['patient_ready'] ?? false) && ($meta['doctor_ready'] ?? false);

        return response()->json([
            'success' => true,
            'patient_ready' => true,
            'doctor_ready' => $meta['doctor_ready'] ?? false,
            'both_ready' => $bothReady
        ]);
    }
}