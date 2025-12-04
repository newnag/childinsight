<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/report', [App\Http\Controllers\AttendanceController::class, 'report'])->name('attendance.report');
    Route::get('/attendance/report/pdf', [App\Http\Controllers\AttendanceController::class, 'reportPdf'])->name('attendance.report_pdf');
    
    Route::resource('health', App\Http\Controllers\HealthController::class);
    
    Route::get('assessments/export-pdf', [App\Http\Controllers\AssessmentController::class, 'exportPdf'])->name('assessments.export_pdf');
    Route::resource('assessments', App\Http\Controllers\AssessmentController::class);
    
    // Admin: Manage Assessment Criteria
    Route::post('assessment_criterias/bulk-update', [App\Http\Controllers\AssessmentCriteriaController::class, 'bulkUpdate'])->name('assessment_criterias.bulk_update');
    Route::resource('assessment_criterias', App\Http\Controllers\AssessmentCriteriaController::class);

    // Admin: Manage Centers
    Route::resource('centers', App\Http\Controllers\CenterController::class);

    // Maintenance
    Route::resource('maintenance', App\Http\Controllers\MaintenanceRequestController::class)->except(['show', 'edit', 'destroy']);

    // Students
    Route::resource('students', App\Http\Controllers\StudentController::class);

    // Daily Logs
    Route::get('/daily-logs', [App\Http\Controllers\DailyLogController::class, 'index'])->name('daily_logs.index');
    Route::get('/daily-logs/create', [App\Http\Controllers\DailyLogController::class, 'create'])->name('daily_logs.create');
    Route::post('/daily-logs', [App\Http\Controllers\DailyLogController::class, 'store'])->name('daily_logs.store');
});

