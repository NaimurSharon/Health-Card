<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorAppointmentController;
use App\Http\Controllers\DoctorPatientController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\TreatmentRequestController;
use App\Http\Controllers\Doctor\DoctorConsultationController;
use App\Http\Controllers\HealthCardController;
use App\Http\Controllers\Doctor\DoctorProfileController;
use App\Http\Controllers\Doctor\DoctorAvailabilityController;

/*
|--------------------------------------------------------------------------
| Doctor Routes
|--------------------------------------------------------------------------
|
| Here are all routes specific to doctors. These routes are protected
| by the 'auth' and 'role:doctor' middleware.
|
*/

// Doctor Routes
Route::prefix('doctor')->name('doctor.')->middleware(['auth', 'role:doctor'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'doctorIndex'])->name('dashboard');
    
    // Appointments
    Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [DoctorAppointmentController::class, 'show'])->name('appointments.show');
    Route::put('/appointments/{appointment}/status', [DoctorAppointmentController::class, 'updateStatus'])->name('appointments.update-status');
    Route::post('/appointments/{appointment}/medical-record', [DoctorAppointmentController::class, 'createMedicalRecord'])->name('appointments.create-medical-record');
    
    // Patients
    Route::get('/patients', [DoctorPatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/{student}', [DoctorPatientController::class, 'show'])->name('patients.show');
    Route::post('/patients/{student}/medical-record', [DoctorPatientController::class, 'createMedicalRecord'])->name('patients.create-medical-record');
    
    // Medical Records
    Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical-records.index');
    Route::get('/medical-records/create', [MedicalRecordController::class, 'create'])->name('medical-records.create');
    Route::post('/medical-records', [MedicalRecordController::class, 'store'])->name('medical-records.store');
    Route::get('/medical-records/{medicalRecord}', [MedicalRecordController::class, 'show'])->name('medical-records.show');
    Route::get('/medical-records/{medicalRecord}/edit', [MedicalRecordController::class, 'edit'])->name('medical-records.edit');
    Route::put('/medical-records/{medicalRecord}', [MedicalRecordController::class, 'update'])->name('medical-records.update');
    Route::delete('/medical-records/{medicalRecord}', [MedicalRecordController::class, 'destroy'])->name('medical-records.destroy');
    
    // Treatment Requests
    Route::get('/treatment-requests', [TreatmentRequestController::class, 'doctorIndex'])->name('treatment-requests.index');
    Route::get('/treatment-requests/{treatmentRequest}', [TreatmentRequestController::class, 'doctorView'])->name('treatment-requests.show');
    Route::put('/treatment-requests/{treatmentRequest}', [TreatmentRequestController::class, 'doctorUpdate'])->name('treatment-requests.update');
    
    // Doctor 
    Route::get('/video-consultations', [DoctorConsultationController::class, 'index'])->name('video-consultation.index');
    Route::get('/video-consultations/{id}', [DoctorConsultationController::class, 'show'])->name('video-consultation.show');
    Route::get('/consultations/{id}', [DoctorConsultationController::class, 'show'])->name('video-consultation.show');
    Route::get('/video-consultations/{id}/join', [DoctorConsultationController::class, 'videoCall'])->name('video-consultation.join');
    Route::post('/video-consultations/{id}/end', [DoctorConsultationController::class, 'endCall'])->name('video-consultation.end');
    Route::post('/video-consultations/{id}/prescription', [DoctorConsultationController::class, 'updatePrescription'])->name('video-consultation.prescription');
    
    Route::get('/consultations/{id}/video-call', [DoctorConsultationController::class, 'videoCall'])->name('consultations.video-call');
    Route::get('/pending-calls', [DoctorConsultationController::class, 'checkPendingCalls'])->name('pending-calls');
    Route::post('/video-calls/accept', [DoctorConsultationController::class, 'acceptCall'])->name('video-calls.accept');
    Route::post('/video-calls/reject', [DoctorConsultationController::class, 'rejectCall'])->name('video-calls.reject');
    Route::post('/video-calls/auto-reject', [DoctorConsultationController::class, 'autoRejectCall'])->name('video-calls.auto-reject');
    Route::post('/video-consultations/{id}/joined', [DoctorConsultationController::class, 'participantJoined'])->name('video-consultation.joined');
    Route::post('/video-consultations/{id}/left', [DoctorConsultationController::class, 'participantLeft'])->name('video-consultation.left');
    Route::post('/video-consultations/{id}/heartbeat', [DoctorConsultationController::class, 'heartbeat'])->name('video-consultation.heartbeat');
    Route::get('/video-consultations/{id}/participants', [DoctorConsultationController::class, 'getParticipants'])->name('video-consultation.participants');
    Route::get('/video-consultations/{id}/notes', [DoctorConsultationController::class, 'getNotes'])->name('video-consultation.get-notes');
    Route::post('/video-consultations/{id}/notes', [DoctorConsultationController::class, 'saveNotes'])->name('video-consultation.save-notes');
    
    // Waiting room endpoints
    Route::get('/video-consultations/{id}/presence', [DoctorConsultationController::class, 'checkPresence'])->name('video-consultation.presence');
    Route::post('/video-consultations/{id}/ready', [DoctorConsultationController::class, 'markReady'])->name('video-consultation.ready');
    
    // Alternative routes for React app (uses /consultations/ instead of /video-consultations/)
    Route::get('/consultations/{id}/presence', [DoctorConsultationController::class, 'checkPresence'])->name('consultations.presence');
    Route::post('/consultations/{id}/ready', [DoctorConsultationController::class, 'markReady'])->name('consultations.ready');
    Route::get('/consultations/{id}/status', [DoctorConsultationController::class, 'checkCallStatus'])->name('consultations.status');
    Route::post('/consultations/{id}/joined', [DoctorConsultationController::class, 'participantJoined'])->name('consultations.joined');
    Route::post('/consultations/{id}/left', [DoctorConsultationController::class, 'participantLeft'])->name('consultations.left');
    Route::post('/consultations/{id}/heartbeat', [DoctorConsultationController::class, 'heartbeat'])->name('consultations.heartbeat');
    Route::get('/consultations/{id}/participants', [DoctorConsultationController::class, 'getParticipants'])->name('consultations.participants');
    
    
    // Health Cards
    Route::get('/health-cards', [HealthCardController::class, 'doctorIndex'])->name('health-cards.index');
    Route::get('/health-cards/{healthCard}', [HealthCardController::class, 'doctorView'])->name('health-cards.show');
    
    Route::put('/profile', [DoctorProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile', [DoctorProfileController::class, 'edit'])->name('profile.edit');
    
    Route::get('/availability', [DoctorAvailabilityController::class, 'index'])->name('availability.index');
    Route::post('/availability', [DoctorAvailabilityController::class, 'updateAvailability'])->name('availability.update');
    Route::post('/leave-dates', [DoctorAvailabilityController::class, 'storeLeaveDate'])->name('availability.leave.store');
    Route::delete('/leave-dates/{leaveDate}', [DoctorAvailabilityController::class, 'destroyLeaveDate'])->name('availability.leave.destroy');
    Route::post('/toggle-availability', [DoctorAvailabilityController::class, 'toggleAvailability'])->name('availability.toggle');
    Route::get('/time-slots/{day}', [DoctorAvailabilityController::class, 'getTimeSlots'])->name('availability.slots');
});
