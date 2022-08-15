<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\EntryController;
use App\Http\Middleware\RUEmployeeMiddleware;
use App\Http\Middleware\RUOrThirdPartyCashierEmployeeMiddleware;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::middleware(RUEmployeeMiddleware::class)->namespace('\App\Http\Controllers')->group(function () {
        Route::get('/menu',             [MenuController::class, 'index'])->name('web.menu.index');
        Route::post('/menu',             [MenuController::class, 'filter'])->name('web.menu.filter');
        Route::get('/menu/create',      [MenuController::class, 'create'])->name('web.menu.create');
        Route::get('/menu/edit/{id}',   [MenuController::class, 'edit'])->name('web.menu.edit');
        Route::post('/menu/form',        [MenuController::class, 'createOrUpdate'])->name('web.menu.createOrUpdate');
        Route::delete('/menu/{date}',      [MenuController::class, 'delete'])->name('web.menu.delete');

        Route::get('/user',                         [UserController::class, 'index'])->name('web.user.index');
        Route::get('/user/create',                  [UserController::class, 'create'])->name('web.user.create');
        Route::post('/user/form',                    [UserController::class, 'form'])->name('web.user.form');
        Route::post('/user/reset-password/${uid}',   [UserController::class, 'resetPassword'])->name('web.user.reset-password');
        // Route::delete('/user/{id}',                 [UserController::class, 'delete'        ]) ->name('web.user.delete'        );
    });

    Route::middleware(RUOrThirdPartyCashierEmployeeMiddleware::class)->namespace('\App\Http\Controllers')->group(function () {
        Route::get('/entry', [EntryController::class, 'index'])->name('web.entry.index');
        Route::get('/ticket', [TicketController::class, 'index'])->name('web.ticket.index');
        Route::get('/report', [ReportController::class, 'index'])->name('web.report.index');
        Route::get('/sell', [\App\Http\Controllers\SellController::class, 'index'])->name('web.sell.index');
    });
});

Route::get('/reset-password/{uid}/{token}', [AuthController::class, 'index'])->name('web.auth.index');
//remover esse abaixo
Route::get('/reset-password', function () {
    return view('auth.reset-password', ['uid' => null, 'token' => null, 'errors' => null]);
})->name("web.auth.resetPassword");

Route::get('/', function () {
    if (Auth::check()) {
        return redirect(config('fortify.home'));
    } else {
        return view('auth.login');
    }
});
