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
        
        $consultations = VideoConsultation::where('student_id', $student->id)
            ->with(['doctor'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $upcomingConsultations = VideoConsultation::where('student_id', $student->id)
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
            ->where('student_id', $student->id)
            ->with(['doctor', 'payment'])
            ->firstOrFail();

        return view('student.video-consultation.show', compact('consultation'));
    }
    
    public function videoCall($id)
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
            ->where('student_id', $student->id)
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
            ->where('student_id', $student->id)
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
            ->where('student_id', $student->id)
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
}