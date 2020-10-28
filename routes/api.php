<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use \App\Http\Controllers\CarController;
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

Route::group(['middleware' => ['cors']], function(){
    Route::group(['prefix' => 'user'], function ()
    {
        Route::post('/register', [UserController::class, 'register'])->name('register');
        Route::post('/login', [UserController::class, 'login'])->name('login');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
    });

    Route::group(['middleware' => ['jwt.verify'], ], function(){
        Route::group([ 'prefix' => 'car'], function(){
            Route::get('/', [CarController::class, 'index'])->name('index');
            Route::post('/create', [CarController::class, 'store'])->name('store');
            Route::get('/{id}', [CarController::class, 'show'])->name('show');
            Route::put('/update/{id}', [CarController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [CarController::class, 'destroy'])->name('destroy');
        });
    });
});
