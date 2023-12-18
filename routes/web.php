<?php

use App\Http\Controllers\Admin\ApartmentController;
use App\Http\Controllers\Owner\FacilityController;
use App\Http\Controllers\Owner\InvoiceController;
use App\Http\Controllers\Owner\RoomController;
use App\Http\Controllers\Owner\TenantAccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\RequestController;
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

Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('/apartments')->group(function () {
        Route::get('/create', [ApartmentController::class, 'create'])->name('apartments.create');
    });
});

Route::middleware(['auth', 'verified','select.apartment', 'apartment.owner', 'role'])->group(function () {
    Route::prefix('/tenant-accounts')->group(function () {
        Route::get('/', [TenantAccountController::class, 'index'])->name('tenant-accounts.index');
        Route::get('/create', [TenantAccountController::class, 'create'])->name('tenant-accounts.create');
        Route::post('/', [TenantAccountController::class, 'store'])->name('tenant-accounts.store');
        Route::delete('/{user}', [TenantAccountController::class, 'destroy'])->name('tenant-accounts.destroy');
    });

    Route::resource('/rooms', RoomController::class)->except('show');
    Route::resource('/facilities', FacilityController::class);
    Route::resource('/invoices', InvoiceController::class)->except(['index', 'show']);
});

Route::middleware(['auth', 'verified', 'select.apartment', 'role'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function () {
        $apartment = \App\Models\Apartment::query()
            ->where('id', '=', \Illuminate\Support\Facades\Session::get('selectedApartmentID'))
            ->first();
        return Inertia::render('Dashboard', [ 'apartment' => $apartment]);
    })->name('dashboard');

    Route::prefix('/requests')->group(function () {
        Route::get('/sent', [RequestController::class, 'indexSent'])->name('requests.sent');
        Route::get('/recv', [RequestController::class, 'indexRecv'])->name('requests.recv');
        Route::post('/submit-form', [RequestController::class, 'store'])->name('requests.store');
        Route::delete('/delete/{id}', [RequestController::class, 'destroy'])->name('requests.destroy');
    });

    Route::get('invoices/', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');

    Route::get('payments/', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/create/invoice/{invoice}', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('payments/invoice/{invoice}', [PaymentController::class, 'store'])->name('payments.store');
    Route::post('payments/approve/invoice/{invoice}', [PaymentController::class, 'approve'])->name('payments.approve');

    Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
});

require __DIR__.'/auth.php';
