<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Principal\PrincipalDashboardController;
use App\Http\Controllers\Principal\PrincipalStudentController;
use App\Http\Controllers\Principal\PrincipalTeacherController;
use App\Http\Controllers\Principal\PrincipalClassController;
use App\Http\Controllers\Principal\PrincipalSectionController;
use App\Http\Controllers\Principal\PrincipalSubjectController;
use App\Http\Controllers\Principal\PrincipalRoutineController;
use App\Http\Controllers\Principal\PrincipalHomeworkController;
use App\Http\Controllers\Principal\PrincipalNoticeController;
use App\Http\Controllers\Principal\PrincipalHealthController;
use App\Http\Controllers\Principal\PrincipalIdCardController;
use App\Http\Controllers\Principal\PrincipalProfileController;

/*
|--------------------------------------------------------------------------
| Principal Routes
|--------------------------------------------------------------------------
|
| Here are all routes specific to principals. These routes are protected
| by the 'auth' and 'role:principal' middleware.
|
*/

Route::prefix('principal')->name('principal.')->middleware(['auth', 'role:principal'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [PrincipalDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/assigned-classes', [PrincipalDashboardController::class, 'assignedClasses'])->name('assigned-classes');

    // Students Management
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [PrincipalStudentController::class, 'index'])->name('index');
        Route::get('/create', [PrincipalStudentController::class, 'create'])->name('create');
        Route::post('/store', [PrincipalStudentController::class, 'store'])->name('store');
        Route::get('/{id}', [PrincipalStudentController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PrincipalStudentController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [PrincipalStudentController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [PrincipalStudentController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/health-records', [PrincipalStudentController::class, 'healthRecords'])->name('health-records');
        Route::get('/{id}/generate-id-card', [PrincipalStudentController::class, 'generateIdCard'])->name('generate-id-card');

        // AJAX Routes
        Route::get('/get-sections/{classId}', [PrincipalStudentController::class, 'getSections'])->name('get-sections');
    });

    // Teachers Management
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', [PrincipalTeacherController::class, 'index'])->name('index');
        Route::get('/create', [PrincipalTeacherController::class, 'create'])->name('create');
        Route::post('/store', [PrincipalTeacherController::class, 'store'])->name('store');
        Route::get('/{id}', [PrincipalTeacherController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PrincipalTeacherController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [PrincipalTeacherController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [PrincipalTeacherController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/assign-classes', [PrincipalTeacherController::class, 'assignClasses'])->name('assign-classes');
        Route::post('/{id}/assign-classes', [PrincipalTeacherController::class, 'storeAssignClasses'])->name('store-assign-classes');
        Route::get('/{id}/generate-id-card', [PrincipalTeacherController::class, 'generateIdCard'])->name('generate-id-card');
    });

    // Classes Management
    Route::prefix('classes')->name('classes.')->group(function () {
        Route::get('/', [PrincipalClassController::class, 'index'])->name('index');
        Route::get('/create', [PrincipalClassController::class, 'create'])->name('create');
        Route::post('/store', [PrincipalClassController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PrincipalClassController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [PrincipalClassController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [PrincipalClassController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/sections', [PrincipalClassController::class, 'sections'])->name('sections');
    });

    // Sections Management
    Route::prefix('sections')->name('sections.')->group(function () {
        Route::get('/', [PrincipalSectionController::class, 'index'])->name('index');
        Route::get('/create', [PrincipalSectionController::class, 'create'])->name('create');
        Route::post('/store', [PrincipalSectionController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PrincipalSectionController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [PrincipalSectionController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [PrincipalSectionController::class, 'destroy'])->name('destroy');
    });

    // Subjects Management
    Route::prefix('subjects')->name('subjects.')->group(function () {
        Route::get('/', [PrincipalSubjectController::class, 'index'])->name('index');
        Route::get('/create', [PrincipalSubjectController::class, 'create'])->name('create');
        Route::post('/store', [PrincipalSubjectController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PrincipalSubjectController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [PrincipalSubjectController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [PrincipalSubjectController::class, 'destroy'])->name('destroy');

        // Teacher Assignment Routes
        Route::get('/assign-teachers', [PrincipalSubjectController::class, 'assignTeachers'])->name('assign-teachers');
        Route::post('/assign-teachers', [PrincipalSubjectController::class, 'storeAssignTeachers'])->name('store-assign-teachers');
        Route::delete('/assignments/{id}', [PrincipalSubjectController::class, 'destroyAssignment'])->name('destroy-assignment');

        // AJAX Routes
        Route::get('/get-sections/{classId}', [PrincipalSubjectController::class, 'getSections'])->name('get-sections');
    });

    Route::prefix('routine')->name('routine.')->group(function () {
        Route::get('/', [PrincipalRoutineController::class, 'index'])->name('index');
        Route::get('/weekly', [PrincipalRoutineController::class, 'weekly'])->name('weekly');
        Route::get('/create', [PrincipalRoutineController::class, 'create'])->name('create');
        Route::post('/store', [PrincipalRoutineController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PrincipalRoutineController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [PrincipalRoutineController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [PrincipalRoutineController::class, 'destroy'])->name('destroy');

        // AJAX Routes - Fixed to match JavaScript calls
        Route::get('/get-sections/{classId}', [PrincipalRoutineController::class, 'getSections'])->name('get-sections');
        Route::get('/get-class-subjects/{classId}', [PrincipalRoutineController::class, 'getClassSubjects'])->name('get-class-subjects');
        Route::get('/get-teachers/{subjectId}', [PrincipalRoutineController::class, 'getTeachers'])->name('get-teachers');
    });
    // Homework Management
    Route::prefix('homework')->name('homework.')->group(function () {
        Route::get('/', [PrincipalHomeworkController::class, 'index'])->name('index');
        Route::get('/create', [PrincipalHomeworkController::class, 'create'])->name('create');
        Route::post('/store', [PrincipalHomeworkController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PrincipalHomeworkController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [PrincipalHomeworkController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [PrincipalHomeworkController::class, 'destroy'])->name('destroy');

        // AJAX Routes
        Route::get('/get-sections/{classId}', [PrincipalHomeworkController::class, 'getSections'])->name('get-sections');
        Route::get('/get-subjects/{classId}/{sectionId}', [PrincipalHomeworkController::class, 'getSubjects'])->name('get-subjects');
    });

    // Notices Management
    Route::prefix('notices')->name('notices.')->group(function () {
        Route::get('/', [PrincipalNoticeController::class, 'index'])->name('index');
        Route::get('/create', [PrincipalNoticeController::class, 'create'])->name('create');
        Route::post('/store', [PrincipalNoticeController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PrincipalNoticeController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [PrincipalNoticeController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [PrincipalNoticeController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/publish', [PrincipalNoticeController::class, 'publish'])->name('publish');
    });

    // Health Records
    Route::prefix('health')->name('health.')->group(function () {
        Route::get('/reports', [PrincipalHealthController::class, 'index'])->name('reports.index');
        Route::get('/reports/{studentId}', [PrincipalHealthController::class, 'studentRecords'])->name('reports.student');
        Route::get('/annual-reports', [PrincipalHealthController::class, 'annualRecords'])->name('reports.annual-records');
        Route::get('/annual-reports/create', [PrincipalHealthController::class, 'createAnnualRecord'])->name('reports.create-annual-record');
        Route::post('/annual-reports/store', [PrincipalHealthController::class, 'storeAnnualRecord'])->name('reports.store-annual-record');
        Route::get('/annual-reports/{id}/edit', [PrincipalHealthController::class, 'editAnnualRecord'])->name('reports.edit-annual-record');
        Route::put('/annual-records/{id}/update', [PrincipalHealthController::class, 'updateAnnualRecord'])->name('update-annual-record');
    });

    // ID Cards Management
    Route::prefix('id-cards')->name('id-cards.')->group(function () {
        Route::get('/', [PrincipalIdCardController::class, 'index'])->name('index');
        Route::get('/templates', [PrincipalIdCardController::class, 'templates'])->name('templates');
        Route::get('/generate-student/{studentId}', [PrincipalIdCardController::class, 'generateStudentCard'])->name('generate-student');
        Route::get('/generate-teacher/{teacherId}', [PrincipalIdCardController::class, 'generateTeacherCard'])->name('generate-teacher');
        Route::post('/bulk-generate', [PrincipalIdCardController::class, 'bulkGenerate'])->name('bulk-generate');
    });

    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [PrincipalProfileController::class, 'index'])->name('index');
        Route::put('/update', [PrincipalProfileController::class, 'update'])->name('update');
        Route::put('/update-password', [PrincipalProfileController::class, 'updatePassword'])->name('update-password');
    });

    // School Management
    Route::prefix('school')->name('school.')->group(function () {
        Route::get('/edit', [PrincipalProfileController::class, 'editSchool'])->name('edit');
        Route::put('/update', [PrincipalProfileController::class, 'updateSchool'])->name('update');
    });
});