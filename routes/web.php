<?php

use Illuminate\Support\Facades\Route;
// --- Import Your Controllers ---
use App\Http\Controllers\Login;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientsController; 
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\AppointmentController; // Optional: If you create one later

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Login Page (Guest only)
Route::get('/', [Login::class, 'index'])->name('login');
Route::post('/login', [Login::class, 'login'])->name('login.submit');

// 2. Logout Route (Must be POST for security)
Route::post('/logout', [Login::class, 'logout'])->name('logout');

// 3. Protected Routes (Only accessible after login)
// You can add 'middleware' => 'auth' or your custom 'isAdmin' middleware here
Route::group(['middleware' => ['isAdmin']], function () {

    // --- Dashboard ---
    Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');

    // --- Appointments ---
    // If you don't have a controller yet, we can just return the view directly
    Route::get('/appointments', function () {
        return view('appointment');
    })->name('appointment');

    // --- Patient Records ---
    Route::get('/patient-records', [PatientsController::class, 'index'])->name('patients.index');

    // --- Reports ---
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/admin/backup-database', [BackupController::class, 'downloadBackup'])
         ->name('admin.db.backup');

    // --- User Accounts (Admins & Staff) ---
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

});