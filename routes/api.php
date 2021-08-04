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

Route::group(['prefix' => 'v1', 'as' => 'v1.'], function (Router $router) {
    $router->group(['prefix' => 'auth', 'as' => 'auth.'], function (Router $router) {
        $router
            ->post('/register', [AuthController::class, 'register'])
            ->name('register');

        $router
            ->post('/login', [AuthController::class, 'login'])
            ->name('login');

        $router
            ->post('/refresh', [AuthController::class, 'refresh'])
            ->name('refresh');

        $router
            ->post('/logout', [AuthController::class, 'logout'])
            ->middleware('auth:api')
            ->name('logout');


        $router->group(['middleware' => 'session.start', 'as' => 'social.'], function (Router $router) {
            $router
                ->get('redirect/{driver}', [SocialiteController::class, 'redirect'])
                ->name('redirect');

            $router
                ->get('callback/{driver}', [SocialiteController::class, 'callback'])
                ->name('callback');
        });
    });
});
