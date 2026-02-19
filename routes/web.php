<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerNoteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer routes
    Route::resource('customers', CustomerController::class);
    Route::patch('/customers/{customer}/restore', [CustomerController::class, 'restore'])
        ->name('customers.restore')
        ->withTrashed();
    Route::patch('/customers/{customer}/assign', [CustomerController::class, 'assign'])
        ->name('customers.assign');

    // Customer Notes routes (nested)
    Route::post('/customers/{customer}/notes', [CustomerNoteController::class, 'store'])
        ->name('customers.notes.store');
    Route::put('/customers/{customer}/notes/{note}', [CustomerNoteController::class, 'update'])
        ->name('customers.notes.update');
    Route::delete('/customers/{customer}/notes/{note}', [CustomerNoteController::class, 'destroy'])
        ->name('customers.notes.destroy');
});

require __DIR__.'/auth.php';
