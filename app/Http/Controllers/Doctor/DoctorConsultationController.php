<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\VideoConsultation;
use App\Services\StreamVideoService;
use Carbon\Carbon;

class DoctorConsultationController extends Controller
{
    protected $streamService;

    public function __construct(StreamVideoService $streamService)
    {
        $this->streamService = $streamService;
    }
    
    public function index()
    {
        $doctor = Auth::user();
        
        // Get all consultations for pagination
        $consultations = VideoConsultation::where('doctor_id', $doctor->id)
            ->with(['student.user', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get today's consultations
        $todayConsultations = VideoConsultation::where('doctor_id', $doctor->id)
            ->whereDate('scheduled_for', Carbon::today())
            ->orWhereDate('created_at', Carbon::today())
            ->get();

        // Get ongoing consultations
        $ongoingConsultations = VideoConsultation::where('doctor_id', $doctor->id)
            ->where('status', 'ongoing')
            ->get();

        // Get upcoming consultations (scheduled for future)
        $upcomingConsultations = VideoConsultation::where('doctor_id', $doctor->id)
            ->where('status', 'scheduled')
            ->where('scheduled_for', '>', now())
            ->orderBy('scheduled_for', 'asc')
            ->get();

        return view('doctor.video-consultation.index', compact(
            'consultations',
            'todayConsultations',
            'ongoingConsultations',
            'upcomingConsultations'
        ));
    }

    public function show($id)
    {
        $doctor = Auth::user();
        
        $consultation = VideoConsultation::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->with(['student.user', 'payment'])
            ->firstOrFail();

        return view('doctor.video-consultation.show', compact('consultation'));
    }

    // New: React app endpoint
    public function videoCall($id)
    {
        $doctor = Auth::user();
        
        $consultation = VideoConsultation::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->with(['student.user'])
            ->firstOrFail();

<<<<<<< HEAD
        // Prevent rejoin to completed calls (like Zoom/Google Meet)
        if ($consultation->status === 'completed') {
            return redirect()->route('doctor.video-consultation.show', $id)
                ->with('message', 'This call has already ended.');
        }

=======
>>>>>>> c356163 (video call ui setup)
        // Update status to ongoing
        if ($consultation->status === 'scheduled') {
            $consultation->update([
                'status' => 'ongoing',
                'started_at' => now()
            ]);
        }

        $streamConfig = $this->streamService->getFrontendConfig(
            $doctor->id, 
            'Dr. ' . $doctor->name,
            $doctor->profile_photo_url ?? null
        );

        $streamConfig['callId'] = $consultation->call_id;

        return view('doctor.video-call-react', compact('consultation', 'streamConfig'));
    }

    // API Endpoints for React
    public function getCallConfig($id)
    {
        $doctor = Auth::user();
        
        $consultation = VideoConsultation::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->with(['student.user'])
            ->firstOrFail();

<<<<<<< HEAD
        // Prevent API access to completed calls
        if ($consultation->status === 'completed') {
            return response()->json([
                'error' => 'This call has already ended and cannot be rejoined.',
                'status' => 'completed'
            ], 403);
        }

=======
>>>>>>> c356163 (video call ui setup)
        $streamConfig = $this->streamService->getFrontendConfig(
            $doctor->id, 
            'Dr. ' . $doctor->name,
            $doctor->profile_photo_url ?? null
        );

        $streamConfig['callId'] = $consultation->call_id;

        return response()->json([
            'consultation' => $consultation,
            'streamConfig' => $streamConfig,
            'userType' => 'doctor'
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $doctor = Auth::user();
        
        $consultation = VideoConsultation::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();

        $request->validate([
            'status' => 'required|in:completed,cancelled',
            'duration' => 'nullable|integer'
        ]);

        $consultation->update([
            'status' => $request->status,
            'ended_at' => now(),
            'duration' => $request->duration
        ]);

        return response()->json(['success' => true]);
    }

    public function saveNotes(Request $request, $id)
    {
        $doctor = Auth::user();
        
        $consultation = VideoConsultation::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();

        $request->validate([
            'notes' => 'nullable|string'
        ]);

        $consultation->update([
            'doctor_notes' => $request->notes
        ]);

        return response()->json(['success' => true]);
    }

    public function endCall(Request $request, $id)
    {
        $doctor = Auth::user();
        
        $consultation = VideoConsultation::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();

<<<<<<< HEAD
        // Get participant count before updating
        $meta = $consultation->call_metadata ?? [];
        $participants = isset($meta['participants']) && is_array($meta['participants']) ? $meta['participants'] : [];
        $participantCount = count($participants);

        // Log the end call event
        \Log::info("Doctor ending call for consultation {$id}. Current participants: {$participantCount}");

        // Always mark as completed when end call is initiated
        // This ensures no rejoin is possible, like Zoom/Google Meet
        $consultation->update([
            'status' => 'completed',
            'ended_at' => now(),
            'duration' => $request->duration ?? (($consultation->started_at) ? now()->diffInSeconds($consultation->started_at) : 0),
            'call_metadata' => array_merge($meta, ['ended_by' => 'doctor', 'ended_at_ts' => now()->timestamp])
        ]);

        // Broadcast the call ended event for real-time update
        try {
            event(new \App\Events\VideoCallEnded($consultation->call_id, 'doctor'));
        } catch (\Exception $e) {
            \Log::warning("Failed to broadcast VideoCallEnded event: " . $e->getMessage());
        }

        // Return full consultation data for display on show page
        return response()->json([
            'success' => true,
            'message' => 'Call ended successfully',
            'consultation' => $consultation->load(['student.user', 'doctor', 'appointment']),
            'redirect_url' => route('doctor.video-consultation.show', $id)
        ]);
=======
        $consultation->update([
            'status' => 'completed',
            'ended_at' => now(),
            'duration' => $request->duration ?? 0
        ]);

        return response()->json(['success' => true]);
>>>>>>> c356163 (video call ui setup)
    }

    /**
     * Endpoint used by frontend when a participant joins the call (doctor side).
     */
    public function participantJoined(Request $request, $id)
    {
        $doctor = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('doctor_id', $doctor->id)
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
     * Return current participants list for doctor-scoped consultation.
     */
    public function getParticipants($id)
    {
        $doctor = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('doctor_id', $doctor->id)
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

        // Persist if changed
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
     * Called by frontend when a participant leaves the call (doctor side).
     */
    public function participantLeft(Request $request, $id)
    {
        $doctor = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('doctor_id', $doctor->id)
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
     * Heartbeat endpoint to mark participant as active (update last_seen) - doctor side
     */
    public function heartbeat(Request $request, $id)
    {
        $doctor = Auth::user();

        $consultation = VideoConsultation::where('id', $id)
            ->where('doctor_id', $doctor->id)
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
<<<<<<< HEAD
}
=======

    /**
     * Check presence of both participants in waiting room
     */
    public function checkPresence($id)
    {
        $doctor = Auth::user();
        $consultation = VideoConsultation::where('id', $id)->where('doctor_id', $doctor->id)->firstOrFail();
        $meta = $consultation->call_metadata ?? [];
        $studentReady = $meta['student_ready'] ?? false;
        $doctorReady = $meta['doctor_ready'] ?? false;
        $now = \Carbon\Carbon::now();
        if (isset($meta['student_ready_at'])) {
            $studentReadyAt = \Carbon\Carbon::parse($meta['student_ready_at']);
            if ($now->diffInSeconds($studentReadyAt) > 10) { $studentReady = false; }
        }
        if (isset($meta['doctor_ready_at'])) {
            $doctorReadyAt = \Carbon\Carbon::parse($meta['doctor_ready_at']);
            if ($now->diffInSeconds($doctorReadyAt) > 10) { $doctorReady = false; }
        }
        return response()->json(['success' => true, 'student_present' => $studentReady, 'doctor_present' => $doctorReady, 'both_ready' => $studentReady && $doctorReady]);
    }

    /**
     * Mark doctor as ready in waiting room
     */
    public function markReady($id)
    {
        $doctor = Auth::user();
        $consultation = VideoConsultation::where('id', $id)->where('doctor_id', $doctor->id)->firstOrFail();
        $meta = $consultation->call_metadata ?? [];
        $meta['doctor_ready'] = true;
        $meta['doctor_ready_at'] = now()->toISOString();
        $consultation->call_metadata = $meta;
        $consultation->save();
        $bothReady = ($meta['student_ready'] ?? false) && ($meta['doctor_ready'] ?? false);
        return response()->json(['success' => true, 'student_ready' => $meta['student_ready'] ?? false, 'doctor_ready' => true, 'both_ready' => $bothReady]);
    }
}
>>>>>>> c356163 (video call ui setup)
