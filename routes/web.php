<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransferController;

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
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    //Route::get('/deposit', [PaymentController::class, 'showDepositForm'])->name('deposit.form');
    Route::post('/deposit', [PaymentController::class, 'deposit'])->name('deposit.store');
    //Route::get('/withdrawal', [PaymentController::class, 'showWithdrawalForm'])->name('withdrawal.form');
    Route::post('/withdrawal', [PaymentController::class, 'withdraw'])->name('withdrawal.store');
    Route::get('/transactions', [PaymentController::class, 'transactionsPage'])->name('transactions.page');
    Route::post('/transfer', [TransferController::class, 'transfer'])->name('transfer.store');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/update-admin-fee', [AdminController::class, 'updateAdminFee'])->name('admin.update.admin_fee');
});
