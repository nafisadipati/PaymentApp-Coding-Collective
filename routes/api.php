<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransferController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth.bearer')->group(function () {
    Route::post('/deposit', [PaymentController::class, 'deposit']);
    Route::post('/withdraw', [PaymentController::class, 'withdraw']);
    Route::post('/transfer', [TransferController::class, 'transfer']);
});
