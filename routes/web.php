<?php

use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HelloDoctorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicConsultationController;
use App\Http\Controllers\ScholarshipController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ============================================================================
// LICENSE ROUTES
// ============================================================================

Route::get('/license-required', function () {
    return view('license-required', [
        'portfolioUrl' => 'https://facreative.biz/',
        'contactEmail' => 'support@facreative.biz',
        'phoneNumber' => '+8801628269707'
    ]);
})->name('license.required');

Route::post('/verify-license', [LicenseController::class, 'verify']);

Route::get('/register-domain', [LicenseController::class, 'showRegistrationForm'])->name('license.register.form');
Route::post('/register-domain', [LicenseController::class, 'processRegistration'])->name('license.register.process');

Route::get('/license-registered/{license_key}', [LicenseController::class, 'showRegistrationSuccess'])
    ->name('license.registered');

// ============================================================================
// PUBLIC ROUTES
// ============================================================================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hospitals/{hospital}', [HospitalController::class, 'view'])->name('hospitals.view');

// Hello Doctor
Route::get('/hello-doctor', [HelloDoctorController::class, 'index'])->name('hello-doctor');
Route::post('/hello-doctor/appointments', [HelloDoctorController::class, 'storeAppointment'])->name('hello-doctor.store-appointment');
Route::post('/hello-doctor/treatment-requests', [HelloDoctorController::class, 'storeTreatmentRequest'])->name('hello-doctor.store-treatment-request');
Route::post('/hello-doctor/instant-video-call', [HelloDoctorController::class, 'createInstantVideoCall'])->name('hello-doctor.instant-video-call');

// Doctors 
Route::get('/doctors/{doctor}', [DoctorController::class, 'view'])->name('doctors.view');

// Scholarship Registration Routes
Route::get('/scholarship/register', [ScholarshipController::class, 'showRegistration'])->name('scholarship.register');

// ============================================================================
// PUBLIC VIDEO CONSULTATION ROUTES (All Authenticated Users)
// ============================================================================
// These routes are accessible to all authenticated users (students, teachers, principals, public)
// They use PublicConsultationController which works with user_id instead of student_id

Route::middleware(['auth'])->group(function () {
    // Video Consultation Routes - Available to all user roles
    Route::get('/video-consultations', [PublicConsultationController::class, 'index'])->name('video-consultation.index');
    Route::get('/consultations/{id}/video-call', [PublicConsultationController::class, 'videoCall'])->name('consultations.video-call');
    Route::get('/video-consultations/create', [PublicConsultationController::class, 'create'])->name('video-consultation.create');
    Route::post('/video-consultations', [PublicConsultationController::class, 'store'])->name('video-consultation.store');
    Route::get('/video-consultations/{id}', [PublicConsultationController::class, 'show'])->name('video-consultation.show');
    Route::get('/video-consultations/{id}/join', [PublicConsultationController::class, 'joinCall'])->name('video-consultation.join');
    Route::post('/video-consultations/{id}/end', [PublicConsultationController::class, 'endCall'])->name('video-consultation.end');
    Route::post('/video-consultations/{id}/joined', [PublicConsultationController::class, 'participantJoined'])->name('video-consultation.joined');
    Route::post('/video-consultations/{id}/left', [PublicConsultationController::class, 'participantLeft'])->name('video-consultation.left');
    Route::post('/video-consultations/{id}/heartbeat', [PublicConsultationController::class, 'heartbeat'])->name('video-consultation.heartbeat');
    Route::get('/video-consultations/{id}/participants', [PublicConsultationController::class, 'getParticipants'])->name('video-consultation.participants');
    
    // Waiting room endpoints
    Route::get('/video-consultations/{id}/presence', [PublicConsultationController::class, 'checkPresence'])->name('video-consultation.presence');
    Route::post('/video-consultations/{id}/ready', [PublicConsultationController::class, 'markReady'])->name('video-consultation.ready');
});

// ============================================================================
// ROLE-SPECIFIC ROUTES NOW IN SEPARATE FILES
// ============================================================================
// Admin routes: routes/admin.php
// API routes: routes/api.php
// Student routes: routes/student.php
// Doctor routes: routes/doctor.php
// Teacher routes: routes/teacher.php
// Principal routes: routes/principal.php

// ============================================================================
// PROFILE ROUTES
// ============================================================================

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================================================
// UTILITY ROUTES
// ============================================================================

// Quick local fix: serve files requested under /build/* from public/build/*
// This helps XAMPP setups where the project is served from /site and
// the built assets live in public/build.
Route::get('/build/{path}', function ($path) {
    $file = public_path('build/' . $path);
    if (!file_exists($file)) {
        abort(404);
    }

    // Set Content-Type based on extension to avoid module MIME type errors
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $mime = match($ext) {
        'js', 'mjs' => 'text/javascript',
        'css' => 'text/css',
        'map', 'json' => 'application/json',
        'wasm' => 'application/wasm',
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg', 'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        default => 'application/octet-stream',
    };

    // Return file response with headers
    return response()->file($file, [
        'Content-Type' => $mime,
        'Content-Length' => filesize($file),
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*');

// Video call route
Route::get('/video-call', function () {
    return view('video-call');
})->name('video-call');

// Auth routes
require __DIR__.'/auth.php';
