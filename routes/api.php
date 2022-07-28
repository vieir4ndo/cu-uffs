<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EntryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TicketController;
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

Route::middleware('auth:sanctum')->get('/user/{uid}', [UserController::class, 'getUser'])->name('api.user.getUser');
Route::middleware('auth:sanctum')->patch('/user/{uid}', [UserController::class, 'updateUserWithoutIdUFFS'])->name('api.user.updateUserWithoutIdUFFS');
Route::middleware('auth:sanctum')->patch('/user/iduffs/{uid}', [UserController::class, 'updateUserWithIdUFFS'])->name('api.user.updateUserWithIdUFFS');
Route::middleware('auth:sanctum')->put('/user/{uid}', [UserController::class, 'changeUserActivity'])->name('api.user.changeUserActivity');
Route::middleware('auth:sanctum')->put('/user', [UserController::class, 'changeUserType'])->name('api.user.changeUserType');
Route::middleware('auth:sanctum')->post('/user', [UserController::class, 'createUserWithoutIdUFFS'])->name('api.user.createWithoutIdUFFS');

Route::middleware('auth:sanctum')->post('/forgot-password/{uid}', [AuthController::class, 'forgotPassword'])->name('api.auth.forgotPassword');
Route::middleware('auth:sanctum')->post('/reset-password/{uid}', [AuthController::class, 'resetPassword'])->name('api.auth.resetPassword');

Route::middleware('auth:sanctum')->post('/ticket/visitor', [TicketController::class, 'insertTicketsForVisitors'])->name('api.ticket.insertTicketsForVisitors');
Route::middleware('auth:sanctum')->post('/ticket/{enrollment_id}', [TicketController::class, 'insertTickets'])->name('api.ticket.insertTickets');

Route::post('/entry/{enrollment_id}', [EntryController::class, 'insertEntry'])->name('api.entry.insertEntry');

Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
Route::post('/user/iduffs', [UserController::class, 'createUserWithIdUFFS'])->name('api.user.createWithIdUFFS');
Route::get('/user/operation/{uid}', [UserController::class, 'getUserOperationStatus'])->name('api.user.getUserOperationStatus');

