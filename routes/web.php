<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now nice to have them all in
| this web.php file but you can also use route attributes in controllers.
|
*/

// Locale switching route
Route::get('/locale/{locale}', [LocaleController::class, 'changeLocale'])->name('locale.change');

// Routes are now defined using Spatie Route Attributes in controllers
// Check app/Http/Controllers/PortalController.php and AdminController.php

/*
 * OLD ROUTES - Now using Route Attributes instead
 *
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
    Route::get('/appointments', [AdminController::class, 'appointments'])->name('admin.appointments');
    Route::get('/services', [AdminController::class, 'services'])->name('admin.services');
});
*/
