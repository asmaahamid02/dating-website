<?php

use App\Http\Controllers\FavoriteUserController;
use App\Http\Controllers\UserController;
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


Route::group(['prefix' => 'v0.1'], function () {
    Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout']);

        ####Start Users Group####
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::patch('/', [UserController::class, 'update']);
        });
        ####End Users Group####

        ####Start Users Favorites####
        Route::group(['prefix' => 'favorites'], function () {
            Route::get('/', [FavoriteUserController::class, 'index']);
        });
        ####End Users Favorites####
    });
});