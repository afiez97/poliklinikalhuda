<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PortalController;
use App\Http\Controllers\AdminController;


// Route::get('/', function () {
//     return view('welcome');
// });




// afiez

// ==== PORTAL ROUTES (public site) ====
Route::get('/', [PortalController::class, 'home'])->name('portal.home');
Route::get('/about', [PortalController::class, 'about'])->name('portal.about');
Route::get('/treatments', [PortalController::class, 'treatments'])->name('portal.treatments');
Route::get('/appointment', [PortalController::class, 'appointmentForm'])->name('portal.appointment');
Route::post('/appointment', [PortalController::class, 'submitAppointment'])->name('portal.appointment.submit');

// ==== ADMIN ROUTES ====
Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'doLogin'])->name('admin.doLogin');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Protected admin routes (add middleware if needed)
Route::prefix('admin')->middleware('auth')->group(function () {
    // Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/appointments', [AdminController::class, 'appointments'])->name('admin.appointments');
    Route::get('/services', [AdminController::class, 'services'])->name('admin.services');
});
