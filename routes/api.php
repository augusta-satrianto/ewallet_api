<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OperatorCardController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('/is-email-exist', [AuthController::class, 'checkEmail']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/midtrans-callback', [TransactionController::class, 'callback']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/userlogin', [AuthController::class, 'user']);
    Route::get('/users', [AuthController::class, 'alluser']);
    Route::get('/transactions', [TransactionController::class, 'index']);

    Route::post('/top_ups', [TransactionController::class, 'createTopUp']);
    Route::post('/transfers', [TransactionController::class, 'createTransfer']);
    Route::post('/data_plan', [TransactionController::class, 'createDataPlan']);

    Route::get('/operator-card', [OperatorCardController::class, 'index']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
