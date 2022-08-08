<?php

use App\Http\Controllers\MenuController;
use App\Http\Middleware\RUEmployeeMiddleware;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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

Route::get('/', function () {
    if (Auth::check()) {
        return redirect(config('fortify.home'));
    } else {
        return view('auth.login');
    }
});


Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::middleware(RUEmployeeMiddleware::class)->namespace('\App\Http\Controllers')->group(function () {
        Route::get   ('/menu',             [MenuController::class, 'index'         ]) ->name('web.menu.index'         );
        Route::post  ('/menu',             [MenuController::class, 'filter'        ]) ->name('web.menu.filter'        );
        Route::get   ('/menu/create',      [MenuController::class, 'create'        ]) ->name('web.menu.create'        );
        Route::get   ('/menu/edit/{id}',   [MenuController::class, 'edit'          ]) ->name('web.menu.edit'          );
        Route::post  ('/menu/form',        [MenuController::class, 'createOrUpdate']) ->name('web.menu.createOrUpdate');
        Route::delete('/menu/{date}',      [MenuController::class, 'delete'        ]) ->name('web.menu.delete'        );
    });
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
