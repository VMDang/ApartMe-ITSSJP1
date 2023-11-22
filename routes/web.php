<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ApartmentController;
use App\Http\Controllers\Owner\TenantAccountController;
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

Route::get('/dashboard', function () {
    $apartment = \App\Models\Apartment::query()
        ->where('id', '=', \Illuminate\Support\Facades\Session::get('selectedApartmentID'))
        ->first();

    return Inertia::render('Dashboard', [ 'apartment' => $apartment]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('/tenant-accounts')->group(function () {
    Route::get('/', [TenantAccountController::class, 'create'])->name('tenant-accounts.view');
    Route::post('/web/submit-form', [TenantAccountController::class, 'store'])->name('tenant-accounts.create');
    Route::delete('/delete/{id}', [TenantAccountController::class, 'destroy'])->name('tenant-accounts.destroy');
});

Route::prefix('/apartments')->group(function () {
    Route::get('/create', [ApartmentController::class, 'create'])->name('apartments.create');
});

require __DIR__.'/auth.php';
