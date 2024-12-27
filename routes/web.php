<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ParticipantController;

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

Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/dashboard/update-status/{id}', [AdminController::class, 'updateStatus'])->name('update.status');
    Route::post('/admin/dashboard/update-status-no/{id}', [AdminController::class, 'updateStatusFail'])->name('update.status.fail');
    // Route::get('/admin/dashboard/data-transaction-user/{id}', [AdminController::class, 'dataTransactionUser'])->name('admin.data-transaction-user');

    Route::get('/admin/history-transaction', [AdminController::class, 'historyTransaction'])->name('admin.history-transaction');
    Route::post('admin/history-transaction', [AdminController::class, 'historyTransaction'])->name('admin.history-transaction');
});

Route::middleware(['auth', 'role:2'])->group(function () {
    Route::get('/', [ParticipantController::class, 'dashboard'])->name('participant.dashboard');
    Route::post('/dashboard/store', [ParticipantController::class, 'store'])->name('participant.dashboard.store');
});

Route::middleware('guest')->group(function () {


    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);

    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register/store', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/register/otp', [RegisterController::class, 'otp'])->name('register.otp');
    Route::post('/register/otp/check', [RegisterController::class, 'otpCheck'])->name('register.otp.check');
    Route::get('/register/forgot-password', [RegisterController::class, 'forgotPasswod'])->name('register.forgot.password');
    Route::post('/register/forgot-password/check', [RegisterController::class, 'forgotPasswordCheck'])->name('register.forgot.password.check');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
