<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Student\CityNoticesController;
use App\Http\Controllers\Student\SchoolDiaryController;
use App\Http\Controllers\HelloDoctorController;
use App\Http\Controllers\Student\ScholarshipController;
use App\Http\Controllers\Student\SchoolNoticesController;
use App\Http\Controllers\Student\StudentIdCardController;
use App\Http\Controllers\Student\StudentHealthReportController;
use App\Http\Controllers\Student\StudentConsultationController;
use App\Http\Controllers\Student\ExamController;

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
|
| Here are all routes specific to students. These routes are protected
| by the 'auth' and 'role:student' middleware.
|
*/

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'studentIndex'])->name('dashboard');
    // Route::get('/', [HomeController::class, 'index'])->name('home');
    
    // Health Report
    Route::get('/health-reports', [StudentHealthReportController::class, 'index'])->name('health-report.index');
    Route::get('/health-report', [StudentHealthReportController::class, 'show'])->name('health-report');
    Route::post('/health-report/upload-prescription', [StudentHealthReportController::class, 'uploadPrescription'])->name('health-report.upload-prescription');
    Route::get('/health-report/print', [StudentHealthReportController::class, 'print'])->name('health-report.print');
    Route::get('/health-report/download-pdf', [StudentHealthReportController::class, 'downloadPdf'])->name('health-report.download-pdf');
    Route::get('/prescription/{id}', [StudentHealthReportController::class, 'viewPrescription'])->name('prescription.view');

    
    // ID Card
    Route::get('/id-card', [StudentIdCardController::class, 'index'])->name('id-card');
    Route::get('/id-card/download', [StudentIdCardController::class, 'download'])->name('id-card.download');
    Route::get('/download/{id}', [StudentIdCardController::class, 'downloadIdCard'])->name('id-cards.download');
    Route::get('/health-card/download', [StudentIdCardController::class, 'downloadHealthCard'])->name('health-card.download');
    Route::get('/verify/{cardNumber}', [StudentIdCardController::class, 'verifyQrCode'])->name('id-cards.verify');
    
    // School Notices
    Route::get('/school-notices', [SchoolNoticesController::class, 'index'])->name('school-notices');
    Route::get('/school-notices/{id}', [SchoolNoticesController::class, 'show'])->name('school-notices.show');
    
    // City Corporation Notices
    Route::get('/city-notices', [CityNoticesController::class, 'index'])->name('city-notices');
    Route::get('/city-notices/{id}', [CityNoticesController::class, 'show'])->name('city-notices.show');
    
    // Print and Download routes
    Route::get('/school-diary/print', [SchoolDiaryController::class, 'printHomework'])->name('school-diary.print');
    Route::get('/school-diary/download-pdf', [SchoolDiaryController::class, 'downloadPdf'])->name('school-diary.download-pdf');
    Route::get('/school-diary/{id}/download-pdf', [SchoolDiaryController::class, 'downloadHomeworkPdf'])->name('school-diary.download-homework-pdf');
    Route::get('/school-diary', [SchoolDiaryController::class, 'index'])->name('school-diary');
    Route::get('/school-diary/today', [SchoolDiaryController::class, 'today'])->name('school-diary.today');
    Route::get('/school-diary/upcoming', [SchoolDiaryController::class, 'upcoming'])->name('school-diary.upcoming');
    Route::get('/school-diary/{id}', [SchoolDiaryController::class, 'show'])->name('school-diary.show');
    
    // Hello Doctor
    Route::get('/hello-doctor', [HelloDoctorController::class, 'index'])->name('hello-doctor');
    // Route::post('/hello-doctor/appointment', [HelloDoctorController::class, 'storeAppointment'])->name('hello-doctor.appointment.store');
    // Route::post('/hello-doctor/treatment-request', [HelloDoctorController::class, 'storeTreatmentRequest'])->name('hello-doctor.treatment-request.store');
    
    Route::post('/hello-doctor/video-consultations', [HelloDoctorController::class, 'storeVideoConsultation'])->name('hello-doctor.store-video-consultation');
    Route::post('/hello-doctor/treatment-requests', [HelloDoctorController::class, 'storeTreatmentRequest'])->name('hello-doctor.store-treatment-request');
    Route::post('/hello-doctor/instant-video-call', [HelloDoctorController::class, 'createInstantVideoCall'])->name('hello-doctor.instant-video-call');
    
    // New instant call routes
    Route::post('/hello-doctor/instant-call', [HelloDoctorController::class, 'initiateInstantCall'])->name('hello-doctor.instant-call');
    Route::get('/hello-doctor/check-availability/{doctorId}', [HelloDoctorController::class, 'checkDoctorAvailability'])->name('hello-doctor.check-availability');
    
    Route::get('/video-consultations', [StudentConsultationController::class, 'index'])->name('video-consultation.index');
    
    Route::get('/consultations/{id}/video-call', [StudentConsultationController::class, 'videoCall'])->name('consultations.video-call');
    Route::get('/video-consultations/create', [StudentConsultationController::class, 'create'])->name('video-consultation.create');
    Route::post('/video-consultations', [StudentConsultationController::class, 'store'])->name('video-consultation.store');
    Route::get('/video-consultations/{id}', [StudentConsultationController::class, 'show'])->name('video-consultation.show');
    Route::get('/video-consultations/{id}/join', [StudentConsultationController::class, 'joinCall'])->name('video-consultation.join');
    Route::post('/video-consultations/{id}/end', [StudentConsultationController::class, 'endCall'])->name('video-consultation.end');
    Route::post('/video-consultations/{id}/joined', [StudentConsultationController::class, 'participantJoined'])->name('video-consultation.joined');
    Route::post('/video-consultations/{id}/left', [StudentConsultationController::class, 'participantLeft'])->name('video-consultation.left');
    Route::post('/video-consultations/{id}/heartbeat', [StudentConsultationController::class, 'heartbeat'])->name('video-consultation.heartbeat');
    Route::get('/video-consultations/{id}/participants', [StudentConsultationController::class, 'getParticipants'])->name('video-consultation.participants');
    Route::get('/video-consultations/{id}/presence', [StudentConsultationController::class, 'checkPresence'])->name('video-consultation.presence');
    Route::post('/video-consultations/{id}/ready', [StudentConsultationController::class, 'markReady'])->name('video-consultation.ready');
    Route::get('/video-consultations/{id}/status', [StudentConsultationController::class, 'checkCallStatus'])->name('video-consultation.status');
    
    // Scholarship Registration Routes
    Route::get('/scholarship/register', [ScholarshipController::class, 'showRegistration'])->name('scholarship.register');
    Route::post('/scholarship/register', [ScholarshipController::class, 'submitRegistration'])->name('scholarship.register.submit');
    Route::get('/scholarship/status', [ScholarshipController::class, 'registrationStatus'])->name('scholarship.status');

    Route::get('/exams', [ExamController::class, 'studentExams'])->name('scholarship');
    Route::get('/exams/{exam}/details', [ExamController::class, 'examDetails'])->name('exam.details');
    Route::get('/exams/{exam}/start', [ExamController::class, 'startExam'])->name('exam.start');
    Route::get('/exam/attempt/{attempt}', [ExamController::class, 'takeExam'])->name('exam.take'); // This is the correct route
    Route::post('/exam/attempt/{attempt}/answer', [ExamController::class, 'submitAnswer'])->name('exam.answer');
    Route::post('/exam/attempt/{attempt}/auto-submit', [ExamController::class, 'autoSubmit'])->name('exam.auto-submit');
    Route::get('/exam/attempt/{attempt}/submit', [ExamController::class, 'submitExam'])->name('exam.submit');
    Route::get('/exam/result/{attempt}', [ExamController::class, 'showResult'])->name('exam.result');
});
