<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlockedUserController;
use App\Http\Controllers\FavoriteUserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v0.1'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('/logout', [AuthController::class, 'logout']);

        ####Start Users Group####
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [UserController::class, 'getUsers']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::post('/edit', [UserController::class, 'update']);
        });
        ####End Users Group####

        ####Start Users Favorites####
        Route::group(['prefix' => 'favorites'], function () {
            Route::get('/', [FavoriteUserController::class, 'getFavoriteUsers']);
            Route::get('/{id}', [FavoriteUserController::class, 'favoriteUser']);
        });
        ####End Users Favorites####

        ####Start Users Block####
        Route::group(['prefix' => 'blocks'], function () {
            Route::get('/', [BlockedUserController::class, 'getBlockedUsers']);
            Route::get('/{id}', [BlockedUserController::class, 'blockUser']);
        });
        ####End Users Block####

        ####Start Messages####
        Route::group(['prefix' => 'messages'], function () {
            Route::get('/{receiver_id}', [MessageController::class, 'getMessages']);
            Route::post('/send/{receiver_id}', [MessageController::class, 'sendMessage']);
        });
        ####End Messages####
    });
});