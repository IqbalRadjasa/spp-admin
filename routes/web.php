<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BillController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;

Route::redirect('/', '/login');

// Auth's Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// User Admin's Routes
Route::middleware([
    'auth',
    'role:super_admin'
])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// All can access Routes
Route::middleware([
    'auth',
    'role:super_admin,tu_staff'
])->group(function () {

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

    Route::post('/bill/{bill}/payments', [PaymentController::class, 'store'])
        ->name('payments.store');
});

// Reports
Route::get('/reports/overdue-report', [ReportController::class, 'overdueReport'])
    ->name('reports.overdue-report');

Route::get('/reports/payment-report', [ReportController::class, 'paymentReport'])
    ->name('reports.payment-report');

Route::get('/reports/payments/export', [ReportController::class, 'exportPayments'])
    ->name('reports.payments.export');

//Receipt
Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])
    ->name('payments.receipt');


require __DIR__ . '/auth.php';
