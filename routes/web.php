<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

use App\Http\Middleware\RUEmployeeMiddleware;

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

// Fix wrong style/mix urls when being served from reverse proxy
URL::forceRootUrl(config('app.url'));

Route::group(['middleware' => ['web']], function () {
    Route::group(['middleware' => ['auth', 'verified']], function () {
    });
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); }) -> name('dashboard');

    Route::middleware(RUEmployeeMiddleware::class)->namespace('\App\Http\Controllers')->group(function () {
        Route::get   ('/menu',             [MenuController::class, 'index'         ]) ->name('web.menu.index'         );
        Route::post  ('/menu',             [MenuController::class, 'filter'        ]) ->name('web.menu.filter'        );
        Route::get   ('/menu/create',      [MenuController::class, 'create'        ]) ->name('web.menu.create'        );
        Route::get   ('/menu/edit/{id}',   [MenuController::class, 'edit'          ]) ->name('web.menu.edit'          );
        Route::post  ('/menu/form',        [MenuController::class, 'createOrUpdate']) ->name('web.menu.createOrUpdate');
        Route::delete('/menu/{date}',      [MenuController::class, 'delete'        ]) ->name('web.menu.delete'        );

        Route::get   ('/user',                         [UserController::class, 'index'         ]) ->name('web.user.index'         );
        Route::get   ('/user/create',                  [UserController::class, 'create'        ]) ->name('web.user.create'        );
        Route::post  ('/user/form',                    [UserController::class, 'form'          ]) ->name('web.user.form'          );
        Route::post  ('/user/reset-password/${uid}',   [UserController::class, 'resetPassword' ]) ->name('web.user.reset-password');
        // Route::delete('/user/{id}',                 [UserController::class, 'delete'        ]) ->name('web.user.delete'        );
    });
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
