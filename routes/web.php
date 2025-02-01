<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransinvoiceController;
use App\Http\Controllers\GetRecordController;
use App\Http\Controllers\AdminLoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/', [LoginController::class, 'store'])->name('login.store');

Route::get('/apply', function () {
    return view('apply2');
})->name('apply');

// Admin Login Routes
Route::prefix('admin')->group(function () {
    Route::get('/records', [AdminLoginController::class, 'index'])->name('admin.login');
    Route::post('/records', [AdminLoginController::class, 'store'])->name('admin.login.store');
});

// Student Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/create', [DashboardController::class, 'create'])->name('dashboard.create');
    Route::post('/dashboard/create', [DashboardController::class, 'store'])->name('dashboard.create');
    Route::get('/dashboard/getDepartments', [DashboardController::class, 'getDepartments'])->name('dashboard.getDepartments');
    Route::get('/dashboard/apply', [DashboardController::class, 'apply'])->name('dashboard.apply');
    Route::get('/dashboard/getDegrees', [DashboardController::class, 'getDegrees'])->name('dashboard.getDegrees');
    Route::get('/dashboard/getFields', [DashboardController::class, 'getFields'])->name('dashboard.getFields');
    Route::post('/dashboard/store', [DashboardController::class, 'store'])->name('dashboard.store');
    Route::post('/dashboard/apply', [DashboardController::class, 'apply'])->name('dashboard.apply');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Cart and Transinvoice Resources
    Route::resource('cart', CartController::class);
    Route::resource('transinvoice', TransinvoiceController::class);

    // Dynamic Record Fetching
    Route::get('/get-departments/{facultyId}', [GetRecordController::class, 'getDepartments']);
    Route::get('/get-degrees/{departmentId}', [GetRecordController::class, 'getDegrees']);
    Route::get('/get-specializations/{degreeId}/{departmentId}', [GetRecordController::class, 'getSpecializations']);
});

// Admin Authenticated Routes
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/processed-record', [DashboardController::class, 'recordProcessed'])->name('recordProcessed');
    Route::get('/approved-record', [DashboardController::class, 'recordApproved'])->name('recordApproved');
    Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');
    Route::post('/process-record', [DashboardController::class, 'processRecord'])->name('processRecord');
    Route::post('/approve', [DashboardController::class, 'approve'])->name('transcriptApprove');
    Route::post('/reject', [DashboardController::class, 'reject'])->name('transcriptReject');
    Route::post('/transcript/submit', [DashboardController::class, 'submitForApproval'])->name('transcriptSubmit');

});
