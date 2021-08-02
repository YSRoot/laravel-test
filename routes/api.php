<?php

use App\Versions\V1\Http\Controllers\Auth\AuthController;
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
            ->post('/login', [AuthController::class, 'login'])->name('login');

        $router
            ->post('/logout', [AuthController::class, 'logout'])
            ->middleware('auth:api')
            ->name('logout');
    });
});
