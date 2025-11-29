<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TreatmentRequest;
use App\Models\Appointment;
use App\Events\VideoCallIncoming;
use App\Models\VideoConsultation;
use App\Services\StreamVideoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HelloDoctorController extends Controller
{
    protected $streamService;

    public function __construct(StreamVideoService $streamService)
    {
        $this->streamService = $streamService;
    }

    public function index()
    {
        $student = Auth::user();
        
        // Get doctors with their details and hospital information
        $doctors = \App\Models\User::where('role', 'doctor')
            ->where('status', 'active')
            ->with(['doctorDetail', 'hospital']) // Changed to singular
            ->get()
            ->map(function($doctor) {
                // Add availability information
                $doctor->today_availability = $this->getDoctorAvailability($doctor->id);
                $doctor->next_available_slot = $this->getNextAvailableSlot($doctor->id);
                return $doctor;
            });
    
        $healthTips = \App\Models\HealthTip::where('status', 'published')
            ->whereIn('target_audience', ['all', 'students'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // if ($student->role === 'student') {
        //     return view('student.hello-doctor.index', compact('doctors', 'healthTips'));
        // }
    
        return view('frontend.hello-doctor.index', compact(
            'doctors', 
            'healthTips'
        ));
    }
    
    public function storeAppointment(Request $request)
    {
        $student = Auth::user();
        
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:500',
            'symptoms' => 'nullable|string',
            'consultation_type' => 'required|in:in_person,video_call',
        ]);

        $appointment = Appointment::create([
            'student_id' => $student->id,
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'reason' => $request->reason,
            'symptoms' => $request->symptoms,
            'status' => 'scheduled',
        ]);

            $this->createVideoConsultation($student->id, $request->doctor_id, $appointment->id, $request);

        return redirect()->route('hello-doctor')->with('success', 'Appointment booked successfully!');
    }

    public function storeTreatmentRequest(Request $request)
    {
        $student = Auth::user();
        
        $request->validate([
            'symptoms' => 'required|string|max:1000',
            'urgency' => 'required|in:emergency,urgent,routine',
            'notes' => 'nullable|string',
            'payment_method' => 'required_if:urgency,emergency|in:card,bkash,nagad,rocket',
            'emergency_contact' => 'required_if:urgency,emergency|string|max:20',
            'consultation_type' => 'required|in:in_person,video_call',
        ]);

        $treatmentRequest = TreatmentRequest::create([
            'student_id' => $student->id,
            'symptoms' => $request->symptoms,
            'urgency' => $request->urgency,
            'priority' => $request->urgency === 'emergency' ? 'emergency' : 'medium',
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        // If it's an emergency video consultation
        if ($request->consultation_type === 'video_call' && $request->urgency === 'emergency') {
            $this->createEmergencyVideoConsultation($student->id, $request);
        }

        return redirect()->route('hello-doctor')->with('success', 'Treatment request submitted successfully!');
    }

    public function createInstantVideoCall(Request $request)
    {
        $student = Auth::user();
        
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'symptoms' => 'required|string|max:1000',
            'urgency' => 'required|in:emergency,urgent',
            'payment_method' => 'required|in:card,bkash,nagad,rocket',
        ]);

        $consultation = $this->createVideoConsultation(
            $student->id, 
            $request->doctor_id, 
            null, 
            $request,
            'instant'
        );

        return redirect()->route('video-consultation.join', $consultation->id)
            ->with('success', 'Video call initiated! Connecting to doctor...');
    }

    private function createVideoConsultation($studentId, $doctorId, $appointmentId = null, $request, $type = 'scheduled')
    {
        $callId = 'vc_' . Str::random(16);

        $consultation = VideoConsultation::create([
            'call_id' => $callId,
            'student_id' => $studentId,
            'doctor_id' => $doctorId,
            'appointment_id' => $appointmentId,
            'type' => $type,
            'symptoms' => $request->symptoms ?? $request->reason,
            'scheduled_for' => $request->appointment_date ?? now()->addMinutes(5),
            'consultation_fee' => $type === 'instant' ? 500 : 0,
            'status' => 'scheduled',
            'payment_status' => $type === 'instant' ? 'pending' : 'free',
        ]);

        // Note: No backend API call to Stream - call creation happens in frontend
        // when the first participant calls call.join({ create: true })

        // Trigger the VideoCallIncoming event
        $this->triggerCallNotification($consultation, $studentId, $doctorId);

        return $consultation;
    }

    private function createEmergencyVideoConsultation($studentId, $request)
    {
        // Find available emergency doctor
        $emergencyDoctor = \App\Models\User::where('role', 'doctor')
            ->where('status', 'active')
            ->where('is_available', true)
            ->first();

        if (!$emergencyDoctor) {
            \Log::warning('No available emergency doctor found for student: ' . $studentId);
            return null;
        }

        $callId = 'emergency_vc_' . Str::random(12);

        $consultation = VideoConsultation::create([
            'call_id' => $callId,
            'student_id' => $studentId,
            'doctor_id' => $emergencyDoctor->id,
            'type' => 'emergency',
            'symptoms' => $request->symptoms,
            'scheduled_for' => now()->addMinutes(2), // Emergency calls start quickly
            'consultation_fee' => 1000, // Higher fee for emergency
            'status' => 'scheduled',
            'payment_status' => 'pending',
            'urgency' => 'emergency',
        ]);

        // Trigger emergency call notification
        $this->triggerEmergencyCallNotification($consultation, $studentId, $emergencyDoctor->id);

        return $consultation;
    }

    private function triggerCallNotification($consultation, $studentId, $doctorId)
    {
        try {
            // Get student and class information
            $student = \App\Models\Student::with(['user', 'class'])->find($studentId);
            
            $callData = [
                'id' => $consultation->id,
                'call_id' => $consultation->call_id,
                'student_id' => $studentId,
                'doctor_id' => $doctorId,
                'student_name' => $student->user->name ?? 'Student',
                'student_class' => $student->class->name ?? 'N/A',
                'symptoms' => $consultation->symptoms,
                'type' => $consultation->type,
                'fee' => $consultation->consultation_fee,
                'created_at' => $consultation->created_at->toISOString(),
            ];

            // Broadcast the call event to the specific doctor
            event(new VideoCallIncoming($callData));

        } catch (\Exception $e) {
            \Log::error('Failed to trigger call notification:', [
                'error' => $e->getMessage(),
                'consultation_id' => $consultation->id
            ]);
        }
    }

    private function triggerEmergencyCallNotification($consultation, $studentId, $doctorId)
    {
        try {
            // Get student information
            $student = \App\Models\Student::with(['user', 'class'])->find($studentId);
            
            $emergencyCallData = [
                'id' => $consultation->id,
                'call_id' => $consultation->call_id,
                'student_id' => $studentId,
                'doctor_id' => $doctorId,
                'student_name' => $student->user->name ?? 'Student',
                'student_class' => $student->class->name ?? 'N/A',
                'symptoms' => $consultation->symptoms,
                'type' => 'emergency',
                'fee' => $consultation->consultation_fee,
                'urgency' => 'emergency',
                'created_at' => $consultation->created_at->toISOString(),
                'is_emergency' => true,
            ];

            // Broadcast emergency call event
            event(new VideoCallIncoming($emergencyCallData));

        } catch (\Exception $e) {
            \Log::error('Failed to trigger emergency call notification:', [
                'error' => $e->getMessage(),
                'consultation_id' => $consultation->id
            ]);
        }
    }

    // Method to get video call configuration
    public function getVideoCallConfig($consultationId)
    {
        $student = Auth::user();
        
        $consultation = VideoConsultation::where('id', $consultationId)
            ->where('student_id', $student->id)
            ->firstOrFail();

        // Generate frontend configuration for Stream Video
        $streamConfig = $this->streamService->getFrontendConfig(
            $student->id, 
            $student->name,
            $student->profile_photo_url ?? null
        );

        $streamConfig['callId'] = $consultation->call_id;
        $streamConfig['callType'] = 'default';

        return response()->json([
            'success' => true,
            'streamConfig' => $streamConfig,
            'consultation' => $consultation
        ]);
    }

    // Method to start video call (redirect to join page)
    public function startVideoCall($consultationId)
    {
        $student = Auth::user();
        
        $consultation = VideoConsultation::where('id', $consultationId)
            ->where('student_id', $student->id)
            ->where('status', 'scheduled')
            ->firstOrFail();

        // Update status to ongoing when student starts the call
        $consultation->update([
            'status' => 'ongoing',
            'started_at' => now()
        ]);

        return redirect()->route('video-consultation.join', $consultation->id);
    }

    // Add method to cancel call
    public function cancelCall($consultationId)
    {
        $student = Auth::user();
        
        $consultation = VideoConsultation::where('id', $consultationId)
            ->where('student_id', $student->id)
            ->firstOrFail();

        $consultation->update(['status' => 'cancelled']);

        // Trigger cancellation event
        event(new \App\Events\VideoCallCancelled($consultation->id, $consultation->doctor_id));

        return redirect()->back()->with('success', 'Call cancelled successfully.');
    }

    // Method to complete call (called from frontend when call ends)
    public function completeCall(Request $request, $consultationId)
    {
        $student = Auth::user();
        
        $consultation = VideoConsultation::where('id', $consultationId)
            ->where('student_id', $student->id)
            ->firstOrFail();

        $request->validate([
            'duration' => 'required|integer',
            'end_reason' => 'nullable|string|max:255'
        ]);

        $consultation->update([
            'status' => 'completed',
            'ended_at' => now(),
            'duration' => $request->duration,
            'end_reason' => $request->end_reason
        ]);

        return response()->json(['success' => true]);
    }

    // Method to get call status
    public function getCallStatus($consultationId)
    {
        $student = Auth::user();
        
        $consultation = VideoConsultation::where('id', $consultationId)
            ->where('student_id', $student->id)
            ->firstOrFail();

        return response()->json([
            'status' => $consultation->status,
            'started_at' => $consultation->started_at,
            'ended_at' => $consultation->ended_at,
            'duration' => $consultation->duration
        ]);
    }
    
    // Helper method to get doctor availability
    private function getDoctorAvailability($doctorId)
    {
        $today = strtolower(now()->englishDayOfWeek);
        
        $availability = \App\Models\DoctorAvailability::where('doctor_id', $doctorId)
            ->where('day_of_week', $today)
            ->where('is_available', true)
            ->first();
        
        return $availability;
    }
    
    // Helper method to get next available slot
    private function getNextAvailableSlot($doctorId)
    {
        $today = now();
        $todayDay = strtolower($today->englishDayOfWeek);
        
        // Check today's availability
        $todayAvailability = \App\Models\DoctorAvailability::where('doctor_id', $doctorId)
            ->where('day_of_week', $todayDay)
            ->where('is_available', true)
            ->first();
        
        if ($todayAvailability) {
            $currentTime = $today->format('H:i:s');
            if ($currentTime < $todayAvailability->end_time) {
                return 'Today';
            }
        }
        
        // Check next available day
        for ($i = 1; $i <= 7; $i++) {
            $checkDate = $today->copy()->addDays($i);
            $dayName = strtolower($checkDate->englishDayOfWeek);
            
            $availability = \App\Models\DoctorAvailability::where('doctor_id', $doctorId)
                ->where('day_of_week', $dayName)
                ->where('is_available', true)
                ->first();
                
            if ($availability) {
                return $checkDate->format('D, M j');
            }
        }
        
        return 'Not available';
    }
}