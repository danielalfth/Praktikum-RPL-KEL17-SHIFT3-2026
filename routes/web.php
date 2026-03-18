<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\MedicalRecordController;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () { return redirect()->route('login'); });
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // AJAX queue status (all roles)
    Route::get('/api/queue/status', [QueueController::class, 'apiStatus'])->name('api.queue.status');

    // Patient routes
    Route::middleware('role:patient')->prefix('patient')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'patientDashboard'])->name('patient.dashboard');
        Route::get('/schedule', [ScheduleController::class, 'index'])->name('patient.schedule');
        Route::get('/queue', [QueueController::class, 'index'])->name('patient.queue');
        Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('patient.medical-records');
        Route::get('/medical-records/{medicalRecord}', [MedicalRecordController::class, 'show'])->name('patient.medical-records.show');
    });

    // Doctor routes
    Route::middleware('role:doctor')->prefix('doctor')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'doctorDashboard'])->name('doctor.dashboard');
        Route::get('/schedule', [ScheduleController::class, 'index'])->name('doctor.schedule');
        Route::put('/schedule/{schedule}/quota', [ScheduleController::class, 'updateQuota'])->name('doctor.schedule.updateQuota');
        Route::get('/queue', [QueueController::class, 'index'])->name('doctor.queue');
        Route::put('/queue/{queue}/status', [QueueController::class, 'updateStatus'])->name('doctor.queue.updateStatus');
        Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('doctor.medical-records');
        Route::get('/medical-records/create/{queue}', [MedicalRecordController::class, 'create'])->name('doctor.medical-records.create');
        Route::post('/medical-records', [MedicalRecordController::class, 'store'])->name('doctor.medical-records.store');
        Route::get('/medical-records/{medicalRecord}', [MedicalRecordController::class, 'show'])->name('doctor.medical-records.show');
        Route::get('/medical-records/{medicalRecord}/edit', [MedicalRecordController::class, 'edit'])->name('doctor.medical-records.edit');
        Route::put('/medical-records/{medicalRecord}', [MedicalRecordController::class, 'update'])->name('doctor.medical-records.update');
    });

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::get('/schedule', [ScheduleController::class, 'index'])->name('admin.schedule');
        Route::get('/queue', [QueueController::class, 'index'])->name('admin.queue');
        Route::post('/queue', [QueueController::class, 'store'])->name('admin.queue.store');
        Route::put('/queue/{queue}/status', [QueueController::class, 'updateStatus'])->name('admin.queue.updateStatus');
        Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('admin.medical-records');
        Route::get('/medical-records/{medicalRecord}', [MedicalRecordController::class, 'show'])->name('admin.medical-records.show');
    });
});
