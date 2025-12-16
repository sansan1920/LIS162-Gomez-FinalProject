<?php

use App\Http\Controllers\ReservationController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\MyReservationController;
use App\Http\Controllers\AdminReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])
    ->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [ReservationController::class, 'index'])
        ->name('dashboard');
    Route::get('api/available-slots', [ReservationController::class, 'getAvailableSlots'])
        ->name('api.available-slots');
    Route::post('reservations/store', [ReservationController::class, 'store'])
        ->name('reservations.store');

    Route::get('my-reservations', [MyReservationController::class, 'index'])
        ->name('my-reservations');
        
    Route::post('my-reservations/{reservation}/feedback', [MyReservationController::class, 'submitFeedback'])
        ->name('reservations.submitFeedback');
        Route::delete('my-reservations/{reservation}', [MyReservationController::class, 'destroy'])
        ->name('reservations.destroy');

    Route::get('admin/reservations', [AdminReservationController::class, 'index'])
        ->name('admin.reservations');

    Route::view('profile', 'profile')
        ->name('profile');
});

require __DIR__.'/auth.php';