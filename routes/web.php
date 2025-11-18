<?php

use App\Http\Controllers\Dashboard;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Login;
use App\Http\Controllers\Database;
use App\Http\Controllers\BackupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

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

// Routes protected by the 'isAdmin' middleware
Route::middleware(['isAdmin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [Dashboard::class, 'index']);
    
    // Appointment
    Route::get('/appointment', function () {
        return view('appointment');
    });
    
    // User Management Routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Backup Route
    Route::get('/admin/backup-database', [BackupController::class, 'downloadBackup'])
         ->name('admin.db.backup');

    // Reports Route (Added here so it is protected by login)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});


// Public / Auth Routes
Route::get('/', [Login::class, 'index']);
Route::post('/login', [Login::class, 'login']);