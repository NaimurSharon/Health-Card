<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminScholarshipController;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\AppointmentController;

use App\Http\Controllers\ClassController;
use App\Http\Controllers\CityCorporationNoticeController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorPatientController;

use App\Http\Controllers\ExamController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\HealthCardController;
use App\Http\Controllers\HealthTipController;
use App\Http\Controllers\HelloDoctorController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\HealthReportController;

use App\Http\Controllers\IdCardController;
use App\Http\Controllers\IdCardTemplateController;

use App\Http\Controllers\LicenseController;

use App\Http\Controllers\MedicalRecordController;

use App\Http\Controllers\NoticeController;

use App\Http\Controllers\OrganizationController;

use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicConsultationController;

use App\Http\Controllers\LoginController;

use App\Http\Controllers\RoutineController;

use App\Http\Controllers\ScholarshipExamController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\IndividualMemberController;

use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TreatmentRequestController;

use App\Http\Controllers\UserController;

use App\Http\Controllers\WorkReportController;
use App\Http\Controllers\WebsiteSettingsController;

use App\Http\Controllers\Doctor\DoctorConsultationController;
use App\Http\Controllers\Doctor\DoctorCallController;
use App\Http\Controllers\Doctor\DoctorProfileController;
use App\Http\Controllers\Doctor\DoctorAvailabilityController;
use App\Http\Controllers\EditorController;

use App\Http\Controllers\Teacher\TeacherRoutineController;
use App\Http\Controllers\Teacher\TeacherHomeworkController;
use App\Http\Controllers\Teacher\TeacherDashboardController;

use App\Http\Controllers\Principal\PrincipalDashboardController;
use App\Http\Controllers\Principal\PrincipalRoutineController;
use App\Http\Controllers\Principal\PrincipalHomeworkController;
use App\Http\Controllers\Principal\PrincipalStudentController;
use App\Http\Controllers\Principal\PrincipalTeacherController;
use App\Http\Controllers\Principal\PrincipalClassController;
use App\Http\Controllers\Principal\PrincipalSectionController;
use App\Http\Controllers\Principal\PrincipalSubjectController;
use App\Http\Controllers\Principal\PrincipalNoticeController;
use App\Http\Controllers\Principal\PrincipalProfileController;
use App\Http\Controllers\Principal\PrincipalHealthController;
use App\Http\Controllers\Principal\PrincipalIdCardController;

use App\Http\Controllers\Student\CityNoticesController;
use App\Http\Controllers\Student\SchoolDiaryController;
use App\Http\Controllers\Student\StudentConsultationController;
use App\Http\Controllers\Student\SchoolNoticesController;
use App\Http\Controllers\Student\StudentIdCardController;
use App\Http\Controllers\Student\StudentHealthReportController;

use Illuminate\Support\Facades\Route;




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
    
    // ------------------------------------------------- GLOBAL ROUTES  ---------------------------------------------------------------------
    
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hospitals/{hospital}', [HospitalController::class, 'view'])->name('hospitals.view');

// Hello Doctor
Route::get('/hello-doctor', [HelloDoctorController::class, 'index'])->name('hello-doctor');
// Route::post('/hello-doctor/appointment', [HelloDoctorController::class, 'storeAppointment'])->name('hello-doctor.appointment.store');
// Route::post('/hello-doctor/treatment-request', [HelloDoctorController::class, 'storeTreatmentRequest'])->name('hello-doctor.treatment-request.store');

Route::post('/hello-doctor/appointments', [HelloDoctorController::class, 'storeAppointment'])->name('hello-doctor.store-appointment');
Route::post('/hello-doctor/treatment-requests', [HelloDoctorController::class, 'storeTreatmentRequest'])->name('hello-doctor.store-treatment-request');
Route::post('/hello-doctor/instant-video-call', [HelloDoctorController::class, 'createInstantVideoCall'])->name('hello-doctor.instant-video-call');

// Doctors 

Route::get('/doctors/{doctor}', [DoctorController::class, 'view'])->name('doctors.view');

// Scholarship Registration Routes
Route::get('/scholarship/register', [ScholarshipController::class, 'showRegistration'])->name('scholarship.register');

//------------------------------------------------------ END GLOBAL ROUTES --------------------------------------------------------------------------------------
    
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Students
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::patch('/students/{student}', [StudentController::class, 'update']);
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    
    // Teachers
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');
    Route::get('/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::patch('/teachers/{teacher}', [TeacherController::class, 'update']);
    Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
    
    // Classes
    Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
    Route::get('/classes/create', [ClassController::class, 'create'])->name('classes.create');
    Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
    Route::get('/classes/{class}', [ClassController::class, 'show'])->name('classes.show');
    Route::get('/classes/{class}/edit', [ClassController::class, 'edit'])->name('classes.edit');
    Route::put('/classes/{class}', [ClassController::class, 'update'])->name('classes.update');
    Route::patch('/classes/{class}', [ClassController::class, 'update']);
    Route::delete('/classes/{class}', [ClassController::class, 'destroy'])->name('classes.destroy');
    
    // Sections
    Route::get('/sections', [ClassController::class, 'sectionsIndex'])->name('sections.index');
    Route::get('/sections/create', [ClassController::class, 'sectionsCreate'])->name('sections.create');
    Route::post('/sections', [ClassController::class, 'sectionsStore'])->name('sections.store');
    Route::get('/sections/{section}', [ClassController::class, 'sectionsShow'])->name('sections.show');
    Route::get('/sections/{section}/edit', [ClassController::class, 'sectionsEdit'])->name('sections.edit');
    Route::put('/sections/{section}', [ClassController::class, 'sectionsUpdate'])->name('sections.update');
    Route::patch('/sections/{section}', [ClassController::class, 'sectionsUpdate']);
    Route::delete('/sections/{section}', [ClassController::class, 'sectionsDestroy'])->name('sections.destroy');
    
    // Routines
    Route::get('/routines', [RoutineController::class, 'index'])->name('routines.index');
    Route::get('/routines/create', [RoutineController::class, 'create'])->name('routines.create');
    Route::post('/routines', [RoutineController::class, 'store'])->name('routines.store');
    Route::get('/routines/{routine}', [RoutineController::class, 'show'])->name('routines.show');
    Route::get('/routines/{routine}/edit', [RoutineController::class, 'edit'])->name('routines.edit');
    Route::put('/routines/{routine}', [RoutineController::class, 'update'])->name('routines.update');
    Route::patch('/routines/{routine}', [RoutineController::class, 'update']);
    Route::delete('/routines/{routine}', [RoutineController::class, 'destroy'])->name('routines.destroy');
    Route::get('/routines/class-section', [RoutineController::class, 'showByClassSection'])->name('routines.class-section');
    
    // Subjects
    Route::get('/subjects', [ClassController::class, 'subjectsIndex'])->name('subjects.index');
    Route::get('/subjects/create', [ClassController::class, 'subjectsCreate'])->name('subjects.create');
    Route::post('/subjects', [ClassController::class, 'subjectsStore'])->name('subjects.store');
    Route::get('/subjects/{subject}', [ClassController::class, 'subjectsShow'])->name('subjects.show');
    Route::get('/subjects/{subject}/edit', [ClassController::class, 'subjectsEdit'])->name('subjects.edit');
    Route::put('/subjects/{subject}', [ClassController::class, 'subjectsUpdate'])->name('subjects.update');
    Route::patch('/subjects/{subject}', [ClassController::class, 'subjectsUpdate']);
    Route::delete('/subjects/{subject}', [ClassController::class, 'subjectsDestroy'])->name('subjects.destroy');
    
    // Medical Records
    Route::get('/medical/records', [MedicalRecordController::class, 'index'])->name('medical.records.index');
    Route::get('/medical/records/create', [MedicalRecordController::class, 'create'])->name('medical.records.create');
    Route::post('/medical/records', [MedicalRecordController::class, 'store'])->name('medical.records.store');
    Route::get('/medical/records/{medical_record}', [MedicalRecordController::class, 'show'])->name('medical.records.show');
    Route::get('/medical/records/{medical_record}/edit', [MedicalRecordController::class, 'edit'])->name('medical.records.edit');
    Route::put('/medical/records/{medical_record}', [MedicalRecordController::class, 'update'])->name('medical.records.update');
    Route::patch('/medical/records/{medical_record}', [MedicalRecordController::class, 'update']);
    Route::delete('/medical/records/{medical_record}', [MedicalRecordController::class, 'destroy'])->name('medical.records.destroy');
    Route::get('/medical/students/{student}/records', [MedicalRecordController::class, 'studentRecords'])->name('medical.records.student');
    
    // Health Cards
    Route::get('/health-cards', [HealthCardController::class, 'index'])->name('health-cards.index');
    Route::get('/health-cards/create', [HealthCardController::class, 'create'])->name('health-cards.create');
    Route::post('/health-cards', [HealthCardController::class, 'store'])->name('health-cards.store');
    Route::get('/health-cards/{health_card}', [HealthCardController::class, 'show'])->name('health-cards.show');
    Route::get('/health-cards/{health_card}/edit', [HealthCardController::class, 'edit'])->name('health-cards.edit');
    Route::put('/health-cards/{health_card}', [HealthCardController::class, 'update'])->name('health-cards.update');
    Route::patch('/health-cards/{health_card}', [HealthCardController::class, 'update']);
    Route::delete('/health-cards/{health_card}', [HealthCardController::class, 'destroy'])->name('health-cards.destroy');
    Route::get('/health-cards/{health_card}/print', [HealthCardController::class, 'print'])->name('health-cards.print');
    
    
    // Health Reports
    Route::get('health-reports', [HealthReportController::class, 'index'])->name('health-reports.index');
    Route::get('health-reports/create/{student}', [HealthReportController::class, 'create'])->name('health-reports.create');
    Route::post('health-reports/{student}', [HealthReportController::class, 'store'])->name('health-reports.store');
    Route::get('health-reports/{healthReport}', [HealthReportController::class, 'show'])->name('health-reports.show');
    Route::get('health-reports/{healthReport}/edit', [HealthReportController::class, 'edit'])->name('health-reports.edit');
    Route::put('health-reports/{healthReport}', [HealthReportController::class, 'update'])->name('health-reports.update');
    Route::delete('health-reports/{healthReport}', [HealthReportController::class, 'destroy'])->name('health-reports.destroy');
    
    // Student-specific health reports
    Route::get('users/{user}/health-report', [HealthReportController::class, 'showByStudent'])
         ->name('health-reports.student');
    Route::post('users/{user}/health-report', [HealthReportController::class, 'storeOrUpdate'])
         ->name('health-reports.store-or-update');

    
    // Treatment Requests
    Route::get('/treatment-requests', [TreatmentRequestController::class, 'index'])->name('treatment-requests.index');
    Route::get('/treatment-requests/create', [TreatmentRequestController::class, 'create'])->name('treatment-requests.create');
    Route::post('/treatment-requests', [TreatmentRequestController::class, 'store'])->name('treatment-requests.store');
    Route::get('/treatment-requests/{treatment_request}', [TreatmentRequestController::class, 'show'])->name('treatment-requests.show');
    Route::get('/treatment-requests/{treatment_request}/edit', [TreatmentRequestController::class, 'edit'])->name('treatment-requests.edit');
    Route::put('/treatment-requests/{treatment_request}', [TreatmentRequestController::class, 'update'])->name('treatment-requests.update');
    Route::patch('/treatment-requests/{treatment_request}', [TreatmentRequestController::class, 'update']);
    Route::delete('/treatment-requests/{treatment_request}', [TreatmentRequestController::class, 'destroy'])->name('treatment-requests.destroy');
    
    Route::get('/scholarship-registrations', [AdminScholarshipController::class, 'index'])->name('scholarship.registrations');
    Route::get('/scholarship-registrations/{registration}', [AdminScholarshipController::class, 'show'])->name('scholarship.registrations.show');
    Route::post('/scholarship-registrations/{registration}/approve', [AdminScholarshipController::class, 'approve'])->name('scholarship.registrations.approve');
    Route::post('/scholarship-registrations/{registration}/reject', [AdminScholarshipController::class, 'reject'])->name('scholarship.registrations.reject');
    Route::post('/scholarship-registrations/{registration}/pending', [AdminScholarshipController::class, 'pending'])->name('scholarship.registrations.pending');
    Route::delete('/scholarship-registrations/{registration}', [AdminScholarshipController::class, 'destroy'])->name('scholarship.registrations.destroy');
    Route::post('/scholarship-registrations/bulk-action', [AdminScholarshipController::class, 'bulkAction'])->name('scholarship.registrations.bulk-action');
    
    Route::get('/exams', [ScholarshipExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/create', [ScholarshipExamController::class, 'create'])->name('exams.create');
    Route::post('/exams', [ScholarshipExamController::class, 'store'])->name('exams.store');
    Route::get('/exams/{exam}/edit', [ScholarshipExamController::class, 'edit'])->name('exams.edit');
    Route::put('/exams/{exam}', [ScholarshipExamController::class, 'update'])->name('exams.update');
    Route::delete('/exams/{exam}', [ScholarshipExamController::class, 'destroy'])->name('exams.destroy');
    Route::post('/exams/{exam}/status', [ScholarshipExamController::class, 'updateStatus'])->name('exams.status');
    Route::get('/exams/{exam}/results', [ScholarshipExamController::class, 'results'])->name('exams.results');
    
    // Health Tips
    Route::get('/health-tips', [HealthTipController::class, 'index'])->name('health-tips.index');
    Route::get('/health-tips/create', [HealthTipController::class, 'create'])->name('health-tips.create');
    Route::post('/health-tips', [HealthTipController::class, 'store'])->name('health-tips.store');
    Route::get('/health-tips/{health_tip}', [HealthTipController::class, 'show'])->name('health-tips.show');
    Route::get('/health-tips/{health_tip}/edit', [HealthTipController::class, 'edit'])->name('health-tips.edit');
    Route::put('/health-tips/{health_tip}', [HealthTipController::class, 'update'])->name('health-tips.update');
    Route::patch('/health-tips/{health_tip}', [HealthTipController::class, 'update']);
    Route::delete('/health-tips/{health_tip}', [HealthTipController::class, 'destroy'])->name('health-tips.destroy');
    
    // Notices
    Route::get('notices/diary', [NoticeController::class, 'diary'])->name('notices.diary');
    Route::get('notices/homepage', [NoticeController::class, 'homepage'])->name('notices.homepage');
    Route::get('notices', [NoticeController::class, 'index'])->name('notices.index');
    Route::get('notices/create', [NoticeController::class, 'create'])->name('notices.create');
    Route::post('notices', [NoticeController::class, 'store'])->name('notices.store');
    Route::get('notices/{notice}', [NoticeController::class, 'show'])->name('notices.show');
    Route::get('notices/{notice}/edit', [NoticeController::class, 'edit'])->name('notices.edit');
    Route::put('notices/{notice}', [NoticeController::class, 'update'])->name('notices.update');
    Route::patch('notices/{notice}', [NoticeController::class, 'update']);
    Route::delete('notices/{notice}', [NoticeController::class, 'destroy'])->name('notices.destroy');
    
    // City Corporation Notices Routes
    Route::get('city-corporation-notices', [CityCorporationNoticeController::class, 'index'])->name('city-corporation-notices.index');
    Route::get('city-corporation-notices/create', [CityCorporationNoticeController::class, 'create'])->name('city-corporation-notices.create');
    Route::post('city-corporation-notices', [CityCorporationNoticeController::class, 'store'])->name('city-corporation-notices.store');
    Route::get('city-corporation-notices/{cityCorporationNotice}', [CityCorporationNoticeController::class, 'show'])->name('city-corporation-notices.show');
    Route::get('city-corporation-notices/{cityCorporationNotice}/edit', [CityCorporationNoticeController::class, 'edit'])->name('city-corporation-notices.edit');
    Route::put('city-corporation-notices/{cityCorporationNotice}', [CityCorporationNoticeController::class, 'update'])->name('city-corporation-notices.update');
    Route::patch('city-corporation-notices/{cityCorporationNotice}', [CityCorporationNoticeController::class, 'update']);
    Route::delete('city-corporation-notices/{cityCorporationNotice}', [CityCorporationNoticeController::class, 'destroy'])->name('city-corporation-notices.destroy');
    
    // Extra API routes
    Route::get('city-corporation-notices/school/{schoolId}', [CityCorporationNoticeController::class, 'getSchoolNotices'])->name('city-corporation-notices.school');
    Route::get('city-corporation-notices/public/{schoolId}/{role}', [CityCorporationNoticeController::class, 'getPublicNotices'])->name('city-corporation-notices.public');


    
    // Editor
    Route::get('/editor', [EditorController::class, 'index'])->name('editor.index');
    Route::get('/editor/create', [EditorController::class, 'create'])->name('editor.create');
    Route::post('/editor', [EditorController::class, 'store'])->name('editor.store');
    Route::get('/editor/{editor}', [EditorController::class, 'show'])->name('editor.show');
    Route::get('/editor/{editor}/edit', [EditorController::class, 'edit'])->name('editor.edit');
    Route::put('/editor/{editor}', [EditorController::class, 'update'])->name('editor.update');
    Route::patch('/editor/{editor}', [EditorController::class, 'update']);
    Route::delete('/editor/{editor}', [EditorController::class, 'destroy'])->name('editor.destroy');
    
    // Schools
    Route::get('/schools', [SchoolController::class, 'index'])->name('schools.index');
    Route::get('/schools/create', [SchoolController::class, 'create'])->name('schools.create');
    Route::post('/schools', [SchoolController::class, 'store'])->name('schools.store');
    Route::get('/schools/{school}', [SchoolController::class, 'show'])->name('schools.show');
    Route::get('/schools/{school}/edit', [SchoolController::class, 'edit'])->name('schools.edit');
    Route::put('/schools/{school}', [SchoolController::class, 'update'])->name('schools.update');
    Route::patch('/schools/{school}', [SchoolController::class, 'update']);
    Route::delete('/schools/{school}', [SchoolController::class, 'destroy'])->name('schools.destroy');
    
    // Organizations
    Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations.index');
    Route::get('/organizations/create', [OrganizationController::class, 'create'])->name('organizations.create');
    Route::post('/organizations', [OrganizationController::class, 'store'])->name('organizations.store');
    Route::get('/organizations/{organization}', [OrganizationController::class, 'show'])->name('organizations.show');
    Route::get('/organizations/{organization}/edit', [OrganizationController::class, 'edit'])->name('organizations.edit');
    Route::put('/organizations/{organization}', [OrganizationController::class, 'update'])->name('organizations.update');
    Route::patch('/organizations/{organization}', [OrganizationController::class, 'update']);
    Route::delete('/organizations/{organization}', [OrganizationController::class, 'destroy'])->name('organizations.destroy');
    
    // Individual Members
    Route::get('/individual-members', [UserController::class, 'index'])->name('individual-members.index');
    Route::get('/individual-members/create', [IndividualMemberController::class, 'create'])->name('individual-members.create');
    Route::post('/individual-members', [IndividualMemberController::class, 'store'])->name('individual-members.store');
    Route::get('/individual-members/{individual_member}', [IndividualMemberController::class, 'show'])->name('individual-members.show');
    Route::get('/individual-members/{individual_member}/edit', [IndividualMemberController::class, 'edit'])->name('individual-members.edit');
    Route::put('/individual-members/{individual_member}', [IndividualMemberController::class, 'update'])->name('individual-members.update');
    Route::patch('/individual-members/{individual_member}', [IndividualMemberController::class, 'update']);
    Route::delete('/individual-members/{individual_member}', [IndividualMemberController::class, 'destroy'])->name('individual-members.destroy');
    
    // Hospitals
    Route::get('/hospitals', [HospitalController::class, 'index'])->name('hospitals.index');
    Route::get('/hospitals/create', [HospitalController::class, 'create'])->name('hospitals.create');
    Route::post('/hospitals', [HospitalController::class, 'store'])->name('hospitals.store');
    Route::get('/hospitals/{hospital}', [HospitalController::class, 'show'])->name('hospitals.show');
    Route::get('/hospitals/{hospital}/edit', [HospitalController::class, 'edit'])->name('hospitals.edit');
    Route::put('/hospitals/{hospital}', [HospitalController::class, 'update'])->name('hospitals.update');
    Route::patch('/hospitals/{hospital}', [HospitalController::class, 'update']);
    Route::delete('/hospitals/{hospital}', [HospitalController::class, 'destroy'])->name('hospitals.destroy');
    Route::delete('hospitals/{hospital}/remove-image/{imageIndex}', [HospitalController::class, 'removeImage'])->name('hospitals.removeImage');
    
    // Doctors
    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('/doctors/create', [DoctorController::class, 'create'])->name('doctors.create');
    Route::post('/doctors', [DoctorController::class, 'store'])->name('doctors.store');
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');
    Route::get('/doctors/{doctor}/edit', [DoctorController::class, 'edit'])->name('doctors.edit');
    Route::put('/doctors/{doctor}', [DoctorController::class, 'update'])->name('doctors.update');
    Route::patch('/doctors/{doctor}', [DoctorController::class, 'update']);
    Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy'])->name('doctors.destroy');
    
    
    // Leave dates management
    Route::get('/doctors/{doctor}/leave-dates', [DoctorController::class, 'leaveDates'])->name('doctors.leave-dates');
    Route::post('/doctors/{doctor}/leave-dates', [DoctorController::class, 'storeLeaveDate'])->name('doctors.leave-dates.store');
    Route::delete('/doctors/{doctor}/leave-dates/{leaveDate}', [DoctorController::class, 'destroyLeaveDate'])->name('doctors.leave-dates.destroy');
    
    // Availability toggle
    Route::post('/doctors/{doctor}/toggle-availability', [DoctorController::class, 'toggleAvailability'])->name('doctors.toggle-availability');
    
    // ============================
    // ID Card Routes
    // ============================
    
    Route::get('id-cards', [IdCardController::class, 'index'])->name('id-cards.index');
    Route::get('id-cards/create', [IdCardController::class, 'create'])->name('id-cards.create');
    Route::post('id-cards', [IdCardController::class, 'store'])->name('id-cards.store');
    Route::get('id-cards/{idCard}', [IdCardController::class, 'show'])->name('id-cards.show');
    Route::get('id-cards/{idCard}/edit', [IdCardController::class, 'edit'])->name('id-cards.edit');
    Route::put('id-cards/{idCard}', [IdCardController::class, 'update'])->name('id-cards.update');
    Route::delete('id-cards/{idCard}', [IdCardController::class, 'destroy'])->name('id-cards.destroy');
    
    Route::get('id-cards/{idCard}/print', [IdCardController::class, 'print'])->name('id-cards.print');
    Route::post('id-cards/bulk-print', [IdCardController::class, 'bulkPrint'])->name('id-cards.bulk-print');
    
    
    // ============================
    // ID Card Template Routes
    // ============================
    
    Route::get('id-card-templates', [IdCardTemplateController::class, 'index'])->name('id-card-templates.index');
    Route::get('id-card-templates/create', [IdCardTemplateController::class, 'create'])->name('id-card-templates.create');
    Route::post('id-card-templates/store', [IdCardTemplateController::class, 'store'])->name('id-card-templates.store');
    Route::get('id-card-templates/{template}/edit', [IdCardTemplateController::class, 'edit'])->name('id-card-templates.edit');
    Route::put('id-card-templates/{template}', [IdCardTemplateController::class, 'update'])->name('id-card-templates.update');
    Route::delete('id-card-templates/{template}', [IdCardTemplateController::class, 'destroy'])->name('id-card-templates.destroy');


    Route::get('/exams', [ScholarshipExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/create', [ScholarshipExamController::class, 'create'])->name('exams.create');
    Route::post('/exams', [ScholarshipExamController::class, 'store'])->name('exams.store');
    Route::get('/exams/{exam}', [ScholarshipExamController::class, 'show'])->name('exams.show');
    Route::get('/exams/{exam}/edit', [ScholarshipExamController::class, 'edit'])->name('exams.edit');
    Route::put('/exams/{exam}', [ScholarshipExamController::class, 'update'])->name('exams.update');
    Route::delete('/exams/{exam}', [ScholarshipExamController::class, 'destroy'])->name('exams.destroy');
    Route::post('/exams/{exam}/status', [ScholarshipExamController::class, 'updateStatus'])->name('exams.status');
    Route::get('/exams/{exam}/results', [ScholarshipExamController::class, 'results'])->name('exams.results');
    Route::get('/exams/{exam}/applicants', [ScholarshipExamController::class, 'applicants'])->name('exams.applicants');
    Route::delete('/exam-attempts/{attempt}', [ScholarshipExamController::class, 'destroyAttempt'])->name('exams.attempts.destroy');
    
    // Scholarship Exam Applicants
    Route::get('/scholarship-exams/{scholarship_exam}/applicants', [ScholarshipExamController::class, 'applicants'])->name('scholarship-exams.applicants');
    Route::put('/scholarship-applications/{application}/status', [ScholarshipExamController::class, 'updateApplicationStatus'])->name('scholarship-applications.status');
    Route::delete('/scholarship-applications/{application}', [ScholarshipExamController::class, 'destroyApplication'])->name('scholarship-applications.destroy');
    
    
    // Health Reports Routes
    Route::get('/health-reports', [HealthReportController::class, 'index'])->name('health-reports.index');
    Route::get('/health-reports/student/{user}', [HealthReportController::class, 'showByStudent'])->name('health-reports.student');
    Route::post('/health-reports/student/{user}', [HealthReportController::class, 'storeOrUpdate'])->name('health-reports.store-or-update');
    Route::get('/health-reports/{healthReport}/edit', [HealthReportController::class, 'edit'])->name('health-reports.edit');
    Route::put('/health-reports/{healthReport}', [HealthReportController::class, 'update'])->name('health-reports.update');
    Route::delete('/health-reports/{healthReport}', [HealthReportController::class, 'destroy'])->name('health-reports.destroy');
    
    // Health Report Fields Routes
    Route::get('/health-report-fields/manage', [HealthReportController::class, 'manageFields'])->name('health-report-fields.manage');
    Route::get('/health-report-fields/{field}/edit', [HealthReportController::class, 'editField'])->name('health-report-fields.edit');
    Route::post('/health-report-fields', [HealthReportController::class, 'createField'])->name('health-report-fields.create');
    Route::put('/health-report-fields/{field}', [HealthReportController::class, 'updateField'])->name('health-report-fields.update');
    Route::delete('/health-report-fields/{field}', [HealthReportController::class, 'deleteField'])->name('health-report-fields.delete');
    

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    
        Route::get('/website-settings', [WebsiteSettingsController::class, 'index'])->name('website-settings.index');
        Route::put('/website-settings', [WebsiteSettingsController::class, 'update'])->name('website-settings.update');
        Route::delete('/website-settings/remove-minister', [WebsiteSettingsController::class, 'removeMinister'])->name('website-settings.remove-minister');
    
    // USers 
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::put('/users/update/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    
    // Profile
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [DashboardController::class, 'updatePassword'])->name('profile.password');
});

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
// Student routes: routes/student.php
// Doctor routes: routes/doctor.php
// Teacher routes: routes/teacher.php
// Principal routes: routes/principal.php



// Doctor Routes
// Doctor routes moved to routes/doctor.php



Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/api/video-call/config/{id}', function ($id) {
        $user = auth()->user();
        if ($user->role === 'doctor') {
            return app(\App\Http\Controllers\Doctor\DoctorConsultationController::class)->getCallConfig($id);
        }
        return app(\App\Http\Controllers\PublicConsultationController::class)->getCallConfig($id);
    });

    Route::post('/api/video-call/{id}/end', function ($id) {
        $user = auth()->user();
        $request = request();
        if ($user->role === 'doctor') {
            return app(\App\Http\Controllers\Doctor\DoctorConsultationController::class)->endCall($request, $id);
        }
        return app(\App\Http\Controllers\PublicConsultationController::class)->endCall($request, $id);
    });

    // Backwards-compatible API-prefixed routes used by the React client
    Route::get('/api/video-call/config/{id}', function ($id) {
        $user = auth()->user();
        if ($user->role === 'doctor') {
            return app(\App\Http\Controllers\Doctor\DoctorConsultationController::class)->getCallConfig($id);
        }
        return app(\App\Http\Controllers\PublicConsultationController::class)->getCallConfig($id);
    });

    Route::post('/api/video-call/{id}/end', function ($id) {
        $user = auth()->user();
        $request = request();
        if ($user->role === 'doctor') {
            return app(\App\Http\Controllers\Doctor\DoctorConsultationController::class)->endCall($request, $id);
        }
        return app(\App\Http\Controllers\PublicConsultationController::class)->endCall($request, $id);
    });
    Route::post('/doctor/consultations/{id}/notes', [DoctorConsultationController::class, 'saveNotes']);
    
    // Doctor Call Management Routes
Route::prefix('api/doctor')->group(function () {
    // Call acceptance/rejection
    Route::post('/video-calls/accept', [DoctorCallController::class, 'acceptCall']);
    Route::post('/video-calls/reject', [DoctorCallController::class, 'rejectCall']);
    Route::post('/video-calls/auto-reject', [DoctorCallController::class, 'autoRejectCall']);
    
    Route::post('/video-consultations/{id}/end', [DoctorConsultationController::class, 'endCall'])->name('video-consultation.end');
    
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
        $consultation = \App\Models\VideoConsultation::with(['student.user', 'doctor', 'appointment'])
            ->findOrFail($id);
        
        // Check access
        $user = auth()->user();
        if ($user->role === 'doctor' && $consultation->doctor_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        if ($user->role === 'student' && $consultation->student_id !== $user->student->id) {
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
            ($user->role === 'student' && $consultation->student_id !== $user->student->id)) {
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

Route::post('/api/video-calls/accept', [DoctorCallController::class, 'acceptCall']);
    Route::post('/api/video-calls/reject', [DoctorCallController::class, 'rejectCall']);
    Route::post('/api/video-calls/auto-reject', [DoctorCallController::class, 'autoRejectCall']);
    
    // Get pending calls for polling
    Route::get('/api/doctor/pending-calls', [DoctorCallController::class, 'getPendingCalls']);

});

// Redirect root to admin dashboard
// Route::redirect('/', '/admin/dashboard');

// Auth routes
require __DIR__.'/auth.php';

// Lightweight route to serve the standalone React/Vite video-call app.
// In local/dev mode the Blade will iframe the Vite dev server at :5173.
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
});

// Video call route
Route::get('/video-call', function () {
    return view('video-call');
})->name('video-call');
