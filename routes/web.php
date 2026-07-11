<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicInfoController;
use App\Http\Controllers\Student\PermitController as StudentPermitController;
use App\Http\Controllers\Student\PrayerController as StudentPrayerController;
use App\Http\Controllers\Admin\PermitController as AdminPermitController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\PrayerMonitoringController as AdminPrayerMonitoringController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Student\PasswordController as StudentPasswordController;

Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
// Throttle: max 10 percobaan login per menit per IP untuk cegah brute-force
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Publik
Route::get('/info-mahasiswa', [PublicInfoController::class, 'index'])->name('public.student-info');

// Group Rute Mahasiswa (Grup Auth & Role Mahasiswa)
Route::middleware(['auth', 'role:mahasiswa'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentPermitController::class, 'index'])->name('dashboard');
    Route::post('/permits', [StudentPermitController::class, 'store'])->name('permits.store');
    Route::post('/permits/{permit}/return', [StudentPermitController::class, 'reportReturn'])->name('permits.return');
    Route::get('/permits/latest-status', [StudentPermitController::class, 'latestStatus'])->name('permits.latest-status');
    
    // Rute Absen Shalat
    Route::get('/sholat', [StudentPrayerController::class, 'index'])->name('sholat.index');
    Route::post('/sholat', [StudentPrayerController::class, 'store'])->name('sholat.store');

    // Rute Absen Kegiatan Kustom Mandiri
    Route::get('/activities', [StudentPermitController::class, 'activityIndex'])->name('activities.index');
    Route::post('/activities/{activity}/attendance', [StudentPermitController::class, 'storeActivityAttendance'])->name('activities.attendance');

    // Rute Ganti Password
    Route::get('/password', [StudentPasswordController::class, 'edit'])->name('password.edit');
    Route::post('/password', [StudentPasswordController::class, 'update'])->name('password.update');
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
    Route::post('/students/{student}/lift-suspension', [AdminStudentController::class, 'liftSuspension'])->name('students.liftSuspension');
    
    // Rute Monitoring Absen Shalat
    Route::get('/sholat', [AdminPrayerMonitoringController::class, 'index'])->name('sholat.index');

    // Rute Kegiatan Kustom (Custom Absen)
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
    // Monitoring kehadiran (read-only) — mahasiswa absen mandiri dari akun masing-masing
    Route::get('/activities/{activity}/attendance', [ActivityController::class, 'showAttendance'])->name('activities.attendance.show');

    // Rute Pengaturan Profil Admin
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
});
