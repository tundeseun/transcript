<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransinvoiceController;
use App\Http\Controllers\GetRecordController;



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

Route::get('/', function () {
    return view('index');
});
Route::get('/welcome', function () {
    return view('welcome');
});
Route::get('/apply', function () {
    return view('apply2');
});

// Route::resource('/',LoginController::class);
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/', [LoginController::class, 'store'])->name('login.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/create', [DashboardController::class, 'create'])->name('dashboard.create');
    Route::post('/dashboard/create', [DashboardController::class, 'store'])->name('dashboard.create');
    Route::get('/dashboard/getDepartments', [DashboardController::class, 'getDepartments'])->name('dashboard.getDepartments');
    Route::get('/dashboard/apply', [DashboardController::class, 'apply'])->name('dashboard.apply');
    Route::get('/dashboard/getDegrees', [DashboardController::class, 'getDegrees'])->name('dashboard.getDegrees');
    Route::get('/dashboard/getFields', [DashboardController::class, 'getFields'])->name('dashboard.getFields');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::post('/dashboard/store', [DashboardController::class, 'store'])->name('dashboard.store');
    Route::post('/dashboard/apply', [DashboardController::class, 'apply'])->name('dashboard.apply');

    

});

//AbdulHammed Route Start

Route::resource('cart', CartController::class)->middleware('auth');

Route::resource('transinvoice', TransinvoiceController::class)->middleware('auth');

Route::get('/get-departments/{facultyId}', [GetRecordController::class, 'getDepartments']);
Route::get('/get-degrees/{departmentId}', [GetRecordController::class, 'getDegrees']);
Route::get('/get-specializations/{degreeId}/{departmentId}', [GetRecordController::class, 'getSpecializations']);