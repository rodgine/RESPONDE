<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IncidentReportController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BackupController;

Route::get('/', function () {
    return view('auth.login');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {

    // Admin Routes
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users');
        Route::post('/admin/users', [UserManagementController::class, 'store'])->name('admin.users.store');
        Route::put('/admin/users/{id}', [UserManagementController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/admin/incidents', [IncidentReportController::class, 'adminIndex'])->name('admin.incidents');
        Route::post('/admin/incidents/{id}/assign', [IncidentReportController::class, 'assignResponder'])->name('admin.incidents.assign');
        Route::post('/admin/incidents/{id}/update-status', [IncidentReportController::class, 'updateStatus'])->name('admin.incidents.updateStatus');
        Route::get('/admin/completed-incidents', [IncidentController::class, 'completedIncidents'])->name('admin.completed.incidents');
        Route::get('/admin/completed-incidents/all', [IncidentController::class, 'getAllCompletedJson'])->name('admin.completed-incidents.all');
        Route::get('/admin/print-report/{id}', [IncidentController::class, 'printReport'])->name('admin.print.report');
        Route::get('/admin/generated-report/{id}', [IncidentController::class, 'viewGeneratedReportAdmin'])->name('admin.generated.report');
        Route::get('/admin/completed-incidents/search', [IncidentController::class, 'searchCompletedIncidents'])->name('admin.completed-incidents.search');
        Route::get('/admin/generate-report', [IncidentController::class, 'generateReportPage'])->name('admin.generate.report');
        
        Route::middleware(['auth'])->prefix('admin/backup')->name('admin.backup.')->group(function () {
            Route::get('/', [BackupController::class, 'index'])->name('index');
            Route::post('/run', [BackupController::class, 'runBackup'])->name('run');
            Route::post('/restore/{id}', [BackupController::class, 'restore'])->name('restore');
            Route::delete('/delete/{id}', [BackupController::class, 'delete'])->name('delete');
            Route::get('/download/{id}', [BackupController::class, 'download'])->name('download');
        });
    });

    // Responder Routes
    Route::middleware(['auth', 'role:responder'])->group(function () {
        Route::get('/responder/dashboard', [DashboardController::class, 'responderDashboard'])->name('responder.dashboard')->middleware('role:responder');
        Route::get('/responder/incidents', [IncidentReportController::class, 'assignedIncidents'])->name('responder.assigned-incident');
        Route::post('/responder/incidents/{id}/submit', [IncidentController::class, 'store'])->name('responder.submit-incident');
        Route::get('/responder/assigned-incidents', [IncidentController::class, 'index'])->name('responder.assigned-incidents');
        Route::post('/responder/incidents/{report}/submit', [IncidentController::class, 'store'])->name('responder.incidents.submit');
        Route::get('/responder/completed', [IncidentReportController::class, 'completedIncidents'])->name('responder.completed');
        Route::get('/responder/completed/{id}/report', [IncidentController::class, 'viewGeneratedReport'])->name('responder.generated-report');
        Route::get('responders/{id}/edit', [UserManagementController::class, 'editResponder'])->name('responder.edit');
        Route::put('responders/{id}/update', [UserManagementController::class, 'updateResponder'])->name('responder.update');
    });

    // User Routes
    Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard')->middleware('role:user');
    Route::get('/user/report', function () {return view('user.report');})->name('user.report');
    Route::post('/incident/store', [IncidentReportController::class, 'store'])->name('incident.store');
    Route::get('/user/my-reports', [IncidentReportController::class, 'myReports'])->name('user.myreports');
    Route::get('users/{id}/edit', [UserManagementController::class, 'editUser'])->name('user.edit');
    Route::put('users/{id}/update', [UserManagementController::class, 'updateUser'])->name('user.update');

    // Public Routes
    Route::get('/fetch', [NotificationController::class, 'fetchNotifications'])->name('admin.notifications.fetch');
    Route::post('/admin/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('admin.notifications.read');
    Route::get('/responder/fetch', [NotificationController::class, 'fetchResponderNotifications'])->name('responder.notifications.fetch');
    Route::post('/responder/read/{id}', [NotificationController::class, 'markResponderAsRead'])->name('responder.notifications.read');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

