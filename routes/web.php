<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeLogController;
use App\Http\Controllers\Manager\TimeLogApprovalController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\RoleManagementController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/time-logs', [TimeLogController::class, 'index'])->middleware('can:view logs');
    Route::post('/time-logs', [TimeLogController::class, 'store'])->middleware('can:create logs');
    Route::patch('/time-logs/{timeLog}', [TimeLogController::class, 'update'])->middleware('can:create logs');
});

Route::middleware(['auth', 'can:approve logs'])->group(function () {
    Route::get('/manager/time-logs', [TimeLogApprovalController::class, 'index']);
    Route::post('/manager/time-logs/{timeLog}/approve', [TimeLogApprovalController::class, 'approve']);
    Route::post('/manager/time-logs/{timeLog}/reject', [TimeLogApprovalController::class, 'reject']);
});

Route::middleware(['auth', 'can:view users'])->group(function () {
    Route::get('/admin/users', [UserManagementController::class, 'index']);
});


Route::middleware(['auth', 'can:create users'])->group(function () {
    Route::post('/admin/users', [UserManagementController::class, 'store']);
});

Route::middleware(['auth', 'can:deactivate users'])->group(function () {
    Route::post('/admin/users/{user}/deactivate', [UserManagementController::class, 'deactivate']);
});

Route::middleware(['auth', 'can:view roles'])->group(function () {
    Route::get('/admin/roles', [RoleManagementController::class, 'index']);
});

Route::middleware(['auth', 'can:create roles'])->group(function () {
    Route::post('/admin/roles', [RoleManagementController::class, 'store']);
    Route::post('/admin/roles/{role}/permissions', [RoleManagementController::class, 'updatePermissions']);
});

Route::get('/manager/time-logs/{timeLog}/history', [TimeLogApprovalController::class, 'history'])->middleware(['auth', 'can:approve logs']);



require __DIR__ . '/auth.php';
