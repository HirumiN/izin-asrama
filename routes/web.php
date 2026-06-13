<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Student\PermitController as StudentPermitController;
use App\Http\Controllers\Admin\PermitController as AdminPermitController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;

Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Group Rute Mahasiswa (Grup Auth & Role Mahasiswa)
Route::middleware(['auth', 'role:mahasiswa'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentPermitController::class, 'index'])->name('dashboard');
    Route::post('/permits', [StudentPermitController::class, 'store'])->name('permits.store');
});

// Group Rute Pengelola/Admin (Grup Auth & Role Pengelola)
Route::middleware(['auth', 'role:pengelola'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminPermitController::class, 'index'])->name('dashboard');
    Route::post('/permits/{permit}/approve', [AdminPermitController::class, 'approve'])->name('permits.approve');
    Route::post('/permits/{permit}/reject', [AdminPermitController::class, 'reject'])->name('permits.reject');
    Route::post('/permits/{permit}/return', [AdminPermitController::class, 'markReturned'])->name('permits.return');
    Route::post('/permits/bulk', [AdminPermitController::class, 'bulkAction'])->name('permits.bulk');
    Route::get('/students', [AdminStudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [AdminStudentController::class, 'create'])->name('students.create');
    Route::post('/students', [AdminStudentController::class, 'store'])->name('students.store');
});
