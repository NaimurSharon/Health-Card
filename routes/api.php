<?php

use App\Http\Controllers\Doctor\DoctorConsultationController;
use App\Http\Controllers\Doctor\DoctorCallController;
use App\Http\Controllers\PublicConsultationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here are all API routes for the application. These routes are loaded
| by the RouteServiceProvider and are assigned the "api" middleware group.
|
*/

// Video Call Configuration and Management API
Route::middleware(['auth'])->group(function () {
    // Video call config endpoint
    Route::get('/api/video-call/config/{id}', function ($id) {
        $user = auth()->user();
        if ($user->role === 'doctor') {
            return app(DoctorConsultationController::class)->getCallConfig($id);
        }
        return app(PublicConsultationController::class)->getCallConfig($id);
    });

    // End video call endpoint
    Route::post('/api/video-call/{id}/end', function ($id) {
        $user = auth()->user();
        $request = request();
        if ($user->role === 'doctor') {
            return app(DoctorConsultationController::class)->endCall($request, $id);
        }
        return app(PublicConsultationController::class)->endCall($request, $id);
    });

    // Doctor consultation notes
    Route::post('/doctor/consultations/{id}/notes', [DoctorConsultationController::class, 'saveNotes']);
    
    // Doctor Call Management Routes
    Route::prefix('api/doctor')->group(function () {
        // Call acceptance/rejection
        Route::post('/video-calls/accept', [DoctorCallController::class, 'acceptCall']);
        Route::post('/video-calls/reject', [DoctorCallController::class, 'rejectCall']);
        Route::post('/video-calls/auto-reject', [DoctorCallController::class, 'autoRejectCall']);
        
        Route::post('/video-consultations/{id}/end', [DoctorConsultationController::class, 'endCall'])->name('api.video-consultation.end');
        
        // Get pending calls for polling
        Route::get('/pending-calls', [DoctorCallController::class, 'getPendingCalls']);
        
        // Consultation status updates
        Route::put('/video-consultations/{id}/status', [DoctorConsultationController::class, 'updateStatus']);
        Route::put('/video-consultations/{id}/prescription', [DoctorConsultationController::class, 'updatePrescription']);
    });

    // Patient Consultation Routes (for all non-doctor users)
    Route::prefix('api/patient')->group(function () {
        // Consultation status updates
        Route::put('/video-consultations/{id}/status', [PublicConsultationController::class, 'updateStatus']);
        
        // Call management
        Route::post('/video-calls/cancel', [PublicConsultationController::class, 'cancelCall']);
    });

    // General Video Consultation Routes (accessible by both doctor and patients)
    Route::prefix('api/video-consultations')->group(function () {
        Route::put('/{id}/status', function ($id) {
            // Handle based on user role
            $user = auth()->user();
            if ($user->role === 'doctor') {
                return app(DoctorConsultationController::class)->updateStatus(request(), $id);
            } else {
                return app(PublicConsultationController::class)->updateStatus(request(), $id);
            }
        });
        
        // Get call details
        Route::get('/{id}', function ($id) {
            $consultation = \App\Models\VideoConsultation::with(['doctor', 'user', 'appointment'])
                ->findOrFail($id);
            
            // Check access
            $user = auth()->user();
            if ($user->role === 'doctor' && $consultation->doctor_id !== $user->id) {
                abort(403, 'Unauthorized');
            }
            if (in_array($user->role, ['student', 'teacher', 'principal', 'public']) && $consultation->user_id !== $user->id) {
                abort(403, 'Unauthorized');
            }
            
            return response()->json($consultation);
        });
    });

    // Real-time call events
    Route::prefix('api/video-calls')->group(function () {
        Route::post('/{callId}/end', function ($callId) {
            // Handle call ending via API
            $user = auth()->user();
            $consultation = \App\Models\VideoConsultation::where('call_id', $callId)->firstOrFail();
            
            // Permission check
            if (($user->role === 'doctor' && $consultation->doctor_id !== $user->id) ||
                (in_array($user->role, ['student', 'teacher', 'principal', 'public']) && $consultation->user_id !== $user->id)) {
                abort(403, 'Unauthorized');
            }
            
            $consultation->update([
                'status' => 'completed',
                'ended_at' => now(),
                'duration' => $consultation->started_at ? now()->diffInSeconds($consultation->started_at) : null
            ]);
            
            // Broadcast call ended event
            event(new \App\Events\VideoCallEnded($callId, $user->role));
            
            return response()->json(['success' => true]);
        });
    });

    // Additional API endpoints for video calls
    Route::post('/api/video-calls/accept', [DoctorCallController::class, 'acceptCall']);
    Route::post('/api/video-calls/reject', [DoctorCallController::class, 'rejectCall']);
    Route::post('/api/video-calls/auto-reject', [DoctorCallController::class, 'autoRejectCall']);
    
    // Get pending calls for polling
    Route::get('/api/doctor/pending-calls', [DoctorCallController::class, 'getPendingCalls']);
});
