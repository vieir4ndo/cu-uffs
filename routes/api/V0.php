<?php

use App\Http\Controllers\Api\V0\AuthController;
use App\Http\Controllers\Api\V0\EntryController;
use App\Http\Controllers\Api\V0\MenuController;
use App\Http\Controllers\Api\V0\UserController;
use App\Http\Controllers\Api\V0\TicketController;
use App\Http\Middleware\ApiKeyMiddleware;
use App\Http\Middleware\RUEmployeeMiddleware;
use App\Http\Middleware\ThirdPartyCashierEmployeeMiddleware;
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

Route::middleware('auth:sanctum')->namespace('\App\Http\Controllers\Api\V0')->group(function () {
    Route::get('/user', [UserController::class, 'getUser'])->name('api.v0.user.getUser');
    Route::patch('/user/iduffs', [UserController::class, 'updateUserWithIdUFFS'])->name('api.v0.user.updateUserWithIdUFFS');
    Route::put('/user', [UserController::class, 'changeUserActivity'])->name('api.v0.user.changeUserActivity');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('api.v0.auth.resetPassword');
    Route::get('/entry', [EntryController::class, 'getEntries'])->name('api.v0.entry.getEntries');
    Route::get('/ticket', [TicketController::class, 'getTickets'])->name('api.v0.ticket.getTickets');
    Route::get('/ticket/balance', [TicketController::class, 'getTicketBalance'])->name('api.v0.ticket.getTicketBalance');

    Route::middleware(ThirdPartyCashierEmployeeMiddleware::class)->namespace('\App\Http\Controllers\Api\V0')->group(function () {
        Route::post('/ticket/visitor', [TicketController::class, 'insertTicketsForVisitors'])->name('api.v0.ticket.insertTicketsForVisitors');
        Route::post('/ticket', [TicketController::class, 'insertTickets'])->name('api.v0.ticket.insertTickets');
    });

    Route::middleware(RUEmployeeMiddleware::class)->namespace('\App\Http\Controllers\Api\V0')->group(function () {
        Route::put('/user/type', [UserController::class, 'changeUserType'])->name('api.v0.user.changeUserType');
        Route::post('/user', [UserController::class, 'createUserWithoutIdUFFS'])->name('api.v0.user.createWithoutIdUFFS');
        Route::patch('/user', [UserController::class, 'updateUserWithoutIdUFFS'])->name('api.v0.user.updateUserWithoutIdUFFS');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('api.v0.auth.forgotPassword');
        Route::post('/menu', [MenuController::class, 'createMenu'])->name('api.v0.menu.createMenu');
        Route::patch('/menu/{date}', [MenuController::class, 'updateMenu'])->name('api.v0.menu.updateMenu');
        Route::delete('/menu/{date}', [MenuController::class, 'deleteMenu'])->name('api.v0.menu.deleteMenu');
    });
});

Route::middleware(ApiKeyMiddleware::class)->namespace('\App\Http\Controllers\Api\V0')->group(function () {
    Route::post('/entry/{enrollment_id}', [EntryController::class, 'insertEntry'])->name('api.v0.entry.insertEntry');
});

Route::post('/login', [AuthController::class, 'login'])->name('api.v0.auth.login');
Route::post('/user/iduffs', [UserController::class, 'createUserWithIdUFFS'])->name('api.v0.user.createWithIdUFFS');
Route::get('/user/operation/{uid}', [UserController::class, 'getUserOperationStatus'])->name('api.v0.user.getUserOperationStatus');
Route::get('/menu', [MenuController::class, 'getMenu'])->name('api.v0.menu.getMenu');
