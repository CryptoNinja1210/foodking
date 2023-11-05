<?php

use Illuminate\Support\Facades\Route;
use App\Http\PaymentGateways\Gateways\Senangpay;

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

Route::prefix('payment')->name('payment.')->middleware(['installed'])->group(function () {
    Route::match(['get', 'post'], '/senangpay-webhook/', [Senangpay::class, 'webhook'])->name('senangpay.webhook');
});
