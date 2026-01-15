<?php

use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\TransactionController;
use App\Http\Controllers\Api\v1\StatsController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\v1\AuthController;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::middleware('guest')->group(function () {
            Route::post('register', [AuthController::class, 'register']);
            Route::post('login', [AuthController::class, 'login']);
        });

        Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
    });

    Route::middleware('auth:sanctum')->prefix('user')->group(function () {
        Route::get('me', [UserController::class, 'me']);
        Route::put('me', [UserController::class, 'updateMe']);

        Route::middleware('can:view,user')->get('{user}', [UserController::class, 'show']);
        Route::middleware('can:update,user')->put('{user}', [UserController::class, 'update']);
        Route::middleware('can:delete,user')->delete('{user}', [UserController::class, 'destroy']);
    });

    Route::middleware('auth:sanctum')->prefix('categories')->group(function () {
       Route::get('/', [CategoryController::class, 'index']);
       Route::post('/', [CategoryController::class, 'store']);

       Route::middleware('can:view,category')->get('{category}', [CategoryController::class, 'show']);
       Route::middleware('can:update,category')->put('{category}', [CategoryController::class, 'update']);
       Route::middleware('can:delete,category')->delete('{category}', [CategoryController::class, 'destroy']);
    });

    Route::middleware('auth:sanctum')->prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::post('/', [TransactionController::class, 'store']);

        Route::middleware('can:view,transaction')->get('{transaction}', [TransactionController::class, 'show']);
        Route::middleware('can:update,transaction')->put('{transaction}', [TransactionController::class, 'update']);
        Route::middleware('can:delete,transaction')->delete('{transaction}', [TransactionController::class, 'destroy']);
    });

    Route::middleware('auth:sanctum')->prefix('stats')->group(function () {
        Route::get('summary', [StatsController::class, 'summary']);
    });
});
