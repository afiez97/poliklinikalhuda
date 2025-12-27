<?php

use App\Http\Controllers\Admin\PesakitController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Admin\MedicineController;

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

// ==== ADMIN MEDICINE INVENTORY ROUTES ====
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // Medicine Routes
    Route::resource('medicine', MedicineController::class);
    Route::get('medicine-low-stock', [MedicineController::class, 'lowStock'])->name('medicine.low-stock');
    Route::get('medicine-expiring', [MedicineController::class, 'expiringSoon'])->name('medicine.expiring');
    Route::patch('medicine/{medicine}/update-stock', [MedicineController::class, 'updateStock'])->name('medicine.update-stock');
    Route::get('medicine-stock-report', [MedicineController::class, 'stockReport'])->name('medicine.stock-report');
    Route::patch('medicine-bulk-status', [MedicineController::class, 'bulkUpdateStatus'])->name('medicine.bulk-status');

    // Pesakit Routes
    Route::get('pesakit', [PesakitController::class, 'index'])->name('pesakit.index');
    Route::get('pesakit/create', [PesakitController::class, 'create'])->name('pesakit.create');
    Route::get('pesakit/search', [PesakitController::class, 'search'])->name('pesakit.search');
});

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
