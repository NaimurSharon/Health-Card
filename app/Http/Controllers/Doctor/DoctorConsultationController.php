<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\VideoConsultation;
use App\Models\MedicalRecord;
use App\Services\StreamVideoService;
use Carbon\Carbon;

class DoctorConsultationController extends Controller
{
    protected $streamService;

    public function __construct(StreamVideoService $streamService)
    {
        $this->streamService = $streamService;
    }
    
    public function index(Request $request)
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

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'consultations' => $consultations->items(),
                'todayConsultations' => $todayConsultations,
                'ongoingConsultations' => $ongoingConsultations,
                'upcomingConsultations' => $upcomingConsultations
            ]);
        }

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

        $consultation->update([
            'status' => 'completed',
            'ended_at' => now(),
            'duration' => $request->duration ?? 0
        ]);

        return response()->json(['success' => true]);
    }

    public function updatePrescription(Request $request, $id)
    {
        $doctor = Auth::user();
        $consultation = VideoConsultation::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();

        $request->validate([
            'prescription' => 'required|string',
            'doctor_notes' => 'nullable|string',
            'medication' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        // Update consultation
        $meta = $consultation->call_metadata ?? [];
        if ($request->medication) $meta['medication'] = $request->medication;
        if ($request->follow_up_date) $meta['follow_up_date'] = $request->follow_up_date;

        $consultation->update([
            'prescription' => $request->prescription,
            'doctor_notes' => $request->doctor_notes,
            'call_metadata' => $meta,
        ]);

        // Create Medical Record
        MedicalRecord::create([
            'user_id' => $consultation->user_id,
            'patient_type' => $consultation->patient_type ?? 'student',
            'record_date' => now(),
            'record_type' => 'checkup',
            'symptoms' => $consultation->symptoms ?? 'Video Consultation',
            'diagnosis' => $request->doctor_notes ?? 'Video Consultation Diagnosis',
            'prescription' => $request->prescription,
            'medication' => $request->medication,
            'doctor_notes' => $request->doctor_notes,
            'follow_up_date' => $request->follow_up_date,
            'recorded_by' => $doctor->id
        ]);

        return back()->with('success', 'Prescription updated and medical record created successfully.');
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
        
        // Update doctor heartbeat
        $meta['doctor_last_heartbeat'] = now()->toISOString();
        
        // If call is active and doctor was marked as disconnected, clear it
        if ($consultation->status === 'ongoing' && isset($meta['doctor_disconnect_at'])) {
            unset($meta['doctor_disconnect_at']);
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
                'role' => 'doctor',
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
        $doctor = Auth::user();
        $consultation = VideoConsultation::where('id', $id)->where('doctor_id', $doctor->id)->firstOrFail();
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
     * Mark doctor as ready in waiting room
     */
    public function markReady($id)
    {
        $doctor = Auth::user();
        $consultation = VideoConsultation::where('id', $id)->where('doctor_id', $doctor->id)->firstOrFail();
        $meta = $consultation->call_metadata ?? [];
        
        // Mark doctor as ready
        $meta['doctor_ready'] = true;
        $meta['doctor_ready_at'] = now()->toISOString();
        $meta['doctor_last_heartbeat'] = now()->toISOString();
        
        // Clear disconnect timestamp if reconnecting
        if (isset($meta['doctor_disconnect_at'])) {
            unset($meta['doctor_disconnect_at']);
        }
        
        $consultation->call_metadata = $meta;
        $consultation->save();
        
        $patientReady = $meta['patient_ready'] ?? false;
        $bothReady = $patientReady && $meta['doctor_ready'];
        
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
            'patient_ready' => $patientReady,
            'doctor_ready' => true,
            'both_ready' => $bothReady,
            'call_active' => $consultation->status === 'ongoing',
            'can_start_call' => $bothReady
        ]);
    }
}
