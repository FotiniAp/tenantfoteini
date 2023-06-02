<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);

Route::group(['prefix' => 'admin','middleware' => ['auth:api','admin']], function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/tenant', [UserController::class, 'getTenants']);
    Route::get('/getUserDetails', [UserController::class, 'getUserDetails']);
    Route::post('/user/store/{id?}', [UserController::class, 'store']);
    Route::get('/user/delete/{id}', [UserController::class, 'destroy']);
    Route::get('logout', [AuthController::class, 'logout']);
});