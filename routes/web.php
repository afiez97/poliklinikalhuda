<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| All routes are now defined using Spatie Route Attributes in controllers.
| This provides better organization and keeps route definitions close to
| their controller logic.
|
| Check the following controllers for route definitions:
| - app/Http/Controllers/PortalController.php (Public site routes)
| - app/Http/Controllers/AdminController.php (Admin authentication & dashboard)
| - app/Http/Controllers/Admin/MedicineController.php (Medicine inventory)
| - app/Http/Controllers/Admin/PesakitController.php (Patient management)
| - app/Http/Controllers/LocaleController.php (Language switching)
|
*/

// Alias for Laravel's default login route name (used by auth middleware)
Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');
