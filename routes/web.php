<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('send-otp', [App\Http\Controllers\Auth\LoginController::class, 'sendOtp'])->name('send.otp');
Route::post('verify-otp', [App\Http\Controllers\Auth\LoginController::class, 'verifyOtp'])->name('verify.otp');

Route::get('accounts', [App\Http\Controllers\HomeController::class, 'accounts'])->name('accounts');
Route::get('add-update-popup-account', [App\Http\Controllers\HomeController::class, 'addUpdatePopupAccount'])->name('add.update.popup.account');
Route::post('add-update-account', [App\Http\Controllers\HomeController::class, 'addUpdateAccount'])->name('add.update.account');
Route::get('fund-transfer-form', [App\Http\Controllers\HomeController::class, 'fundTransferForm'])->name('fund.transfer.form');
Route::post('fund-transfer', [App\Http\Controllers\HomeController::class, 'fundTransfer'])->name('fund.transfer');
Route::get('transactions', [App\Http\Controllers\HomeController::class, 'transactions'])->name('transactions');


