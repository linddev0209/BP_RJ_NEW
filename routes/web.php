<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Models\Notification;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/admin/dashboard', function () {
//     return Inertia::render('Manager/Dashboard');
// })->middleware(['auth', 'verified', 'manager'])->name('dashboard');

// Route::get('/admin/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [NotificationController::class, 'index'])->name('dashboard')->middleware(['auth', 'verified']);;
// Route::get('/admin/dashboard', [NotificationController::class, 'index'])->name('dashboard')->middleware(['auth', 'verified']);;

Route::delete('/vacations/{id}', [VacationController::class, 'destroy'])->name('vacations.destroy');
Route::post('/updateVacation', [VacationController::class, 'update']);

Route::post('/notidirect', [NotificationController::class, 'show']);

Route::resource('ferie', VacationController::class)
    ->only(['index', 'store'])
    ->middleware(['auth', 'verified']);

Route::resource('manager_user', UserController::class)
    ->only(['index'])
    ->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::post('/user_authorization_update/{employee_id}', [UserController::class, 'update']);
    Route::post('/user_info_update/{employee_id}', [UserController::class, 'updateInfo']);
    Route::delete('/user_info_delete/{employee_id}', [UserController::class, 'deleteUserInfo']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
