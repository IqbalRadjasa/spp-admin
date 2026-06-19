<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BillController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\SchoolSettingController;
use App\Http\Controllers\studentPromotionController;

Route::redirect('/', '/login');

// Auth's Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin's Routes
Route::middleware([
    'auth',
    'role:super_admin'
])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('settings')
        ->name('settings.')
        ->group(function () {

            // School Setting Routes
            Route::get('/school', [SchoolSettingController::class, 'edit'])
                ->name('school.edit');

            Route::put('/school/update', [SchoolSettingController::class, 'update'])
                ->name('school.update');

            Route::resource('majors', MajorController::class);

            Route::resource('classrooms', ClassroomController::class);

            Route::resource('academic-years', AcademicYearController::class);
        });

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity-logs.index');

    // Get Academic Year
    Route::get('/academic-years/getAcademicYear/{academicYear}', [AcademicYearController::class, 'getAcademicYear']);

    // Activate Academic Year
    Route::put('/academic-years/activateAcademicYear/{academicYear}', [AcademicYearController::class, 'activateAcademicYear'])->name('settings.academic-years.activateAcademicYear');

    // Student Promotion's Routes
    Route::resource('students-promotion', StudentPromotionController::class);
});

// Public Routes
Route::middleware([
    'auth',
    'role:super_admin,tu_staff'
])->group(function () {

    // Student's Routes
    Route::resource('students', StudentController::class);

    // Biil's Routes
    Route::get('/bills', [BillController::class, 'index'])
        ->name('bills.index');

    Route::get('/bills/generate', [BillController::class, 'generateForm'])
        ->name('bills.generate.form');

    Route::get('/bills/{bill}/detail', [BillController::class, 'detail'])
        ->name('bills.detail');

    Route::post('/bills/{bill}/escalation-notes', [BillController::class, 'storeEscalationNote'])
        ->name('bills.escalation-notes.store');

    Route::post('/bills/generate', [BillController::class, 'generateBills'])
        ->name('bills.generate');


    // Payment's Routes
    Route::get('/bills/payments/{bill}/create', [PaymentController::class, 'create'])
        ->name('payments.create');

    Route::post('/bill/{bill}/payments', [PaymentController::class, 'store'])
        ->name('payments.store');

    // Report's Routes
    Route::get('/reports/overdue-report', [ReportController::class, 'overdueReport'])
        ->name('reports.overdue-report');

    Route::get('/reports/payment-report', [ReportController::class, 'paymentReport'])
        ->name('reports.payment-report');


    // Export's Routes
    Route::get('/reports/overdue/export', [ReportController::class, 'exportOverdue'])
        ->name('reports.overdue.export');

    Route::get('/reports/payments/export', [ReportController::class, 'exportPayments'])
        ->name('reports.payments.export');

    //Receipt's Routes
    Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])
        ->name('payments.receipt');


    // Filter Routes
    Route::get('/classrooms/by-major/{major}', [ClassroomController::class, 'byMajor']);
});



require __DIR__ . '/auth.php';
