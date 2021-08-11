<?php

use App\Versions\V1\Http\Controllers\Auth\AuthController;
use App\Versions\V1\Http\Controllers\Auth\SocialiteController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;

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

Passport::routes();

Route::group(['prefix' => 'v1', 'as' => 'v1.'], function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('/register', [AuthController::class, 'register'])
            ->name('register');

        Route::post('/login', [AuthController::class, 'login'])
            ->name('login');

        Route::post('/refresh', [AuthController::class, 'refresh'])
            ->name('refresh');

        Route::post('/logout', [AuthController::class, 'logout'])
            ->middleware('auth:api')
            ->name('logout');


        Route::group(['middleware' => 'session.start', 'as' => 'social.'], function () {
            Route::get('redirect/{driver}', [SocialiteController::class, 'redirect'])
                ->name('redirect');

            Route::get('callback/{driver}', [SocialiteController::class, 'callback'])
                ->name('callback');
        });
    });
});
