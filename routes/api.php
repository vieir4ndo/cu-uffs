<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user/{uid}', [UserController::class, 'getUser'])->name('api.user.get');
Route::middleware('auth:sanctum')->patch('/user/{uid}', [UserController::class, 'updateUser'])->name('api.user.update');
Route::middleware('auth:sanctum')->put('/user/{uid}', [UserController::class, 'changeUserActivity'])->name('api.user.changeUserActivity');
Route::middleware('auth:sanctum')->post('/reset-password/{uid}', [AuthController::class, 'resetPassword'])->name('api.auth.reset-password');

Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
Route::post('/user', [UserController::class, 'createUser'])->name('api.user.create');
Route::post('/forgot-password/{uid}', [AuthController::class, 'forgotPassword'])->name('api.auth.forgot-password');

