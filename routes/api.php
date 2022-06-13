<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\ApiUserSitesController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user', [UserController::class, 'createUser'])
    ->name('api.user.create');
Route::get('/user/{uid}', [UserController::class, 'getUser'])
    ->name('api.user.get');
Route::patch('/user/{uid}', [UserController::class, 'updateUser'])
    ->name('api.user.update');
Route::delete('/user/{uid}', [UserController::class, 'deleteUser'])
    ->name('api.user.delete');
Route::put('/user/{uid}', [UserController::class, 'updateProfilePicture'])
    ->name('api.user.updateProfilePicture');


