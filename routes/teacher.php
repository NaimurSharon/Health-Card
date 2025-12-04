<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherRoutineController;
use App\Http\Controllers\Teacher\TeacherHomeworkController;
use App\Http\Controllers\Teacher\TeacherHealthCardController;

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
|
| Here are all routes specific to teachers. These routes are protected
| by the 'auth' and 'role:teacher' middleware.
|
*/

// Teacher Routes
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:teacher'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [TeacherDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/assigned-classes', [TeacherDashboardController::class, 'assignedClasses'])->name('assigned-classes');
    
    // Routine
    Route::prefix('routine')->name('routine.')->group(function () {
        Route::get('/', [TeacherRoutineController::class, 'index'])->name('index');
        Route::get('/weekly', [TeacherRoutineController::class, 'weekly'])->name('weekly');
    });
    
    // Homework
    Route::prefix('homework')->name('homework.')->group(function () {
        Route::get('/', [TeacherHomeworkController::class, 'index'])->name('index');
        Route::get('/create', [TeacherHomeworkController::class, 'create'])->name('create');
        Route::post('/store', [TeacherHomeworkController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [TeacherHomeworkController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [TeacherHomeworkController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [TeacherHomeworkController::class, 'destroy'])->name('destroy');
        
        // AJAX Routes
        Route::get('/get-sections/{classId}', [TeacherHomeworkController::class, 'getSections'])->name('get-sections');
        Route::get('/get-subjects/{classId}/{sectionId}', [TeacherHomeworkController::class, 'getSubjects'])->name('get-subjects');
    });

    // Health Card
    Route::prefix('health-card')->name('health-card.')->group(function () {
        Route::get('/', [TeacherHealthCardController::class, 'index'])->name('index');
        Route::get('/download-pdf', [TeacherHealthCardController::class, 'downloadPdf'])->name('download-pdf');
        Route::get('/print', [TeacherHealthCardController::class, 'print'])->name('print');
    });
});