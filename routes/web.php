<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BillController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;

Route::redirect('/', '/login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Auth's Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Student's Routes
Route::resource('students', StudentController::class);

// Biil's Routes
Route::get('/bills/generate', [BillController::class, 'generateForm'])
    ->name('bills.generate.form');

Route::post('/bills/generate', [BillController::class, 'generateBills'])
    ->name('bills.generate');

Route::get('/bills', [BillController::class, 'index'])
    ->name('bills.index');

// Payment's Routes
Route::get('/payments/{bill}/create', [PaymentController::class, 'create'])
    ->name('payments.create');

Route::post('/payments/{bill}', [PaymentController::class, 'store'])
    ->name('payments.store');

require __DIR__ . '/auth.php';
