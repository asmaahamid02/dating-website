<?php

use App\Http\Controllers\BlockedUserController;
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
    Route::group(['middleware' => 'api'], function () {
        Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
        Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
        Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout']);

        ####Start Users Group####
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [UserController::class, 'getUsers']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::patch('/', [UserController::class, 'update']);
        });
        ####End Users Group####

        ####Start Users Favorites####
        Route::group(['prefix' => 'favorites'], function () {
            Route::get('/', [FavoriteUserController::class, 'getFavoriteUsers']);
            Route::get('/{id}', [FavoriteUserController::class, 'updateFavoriteStatus']);
        });
        ####End Users Favorites####

        ####Start Users Block####
        Route::group(['prefix' => 'blocks'], function () {
            Route::get('/', [BlockedUserController::class, 'getBlockedUsers']);
            Route::get('/{id}', [BlockedUserController::class, 'updateBlockStatus']);
        });
        ####End Users Block####
    });
});