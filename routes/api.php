<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserIteractionController;
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

Route::group([
    'middleware' => 'cors',
], function ($router) {

    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::group([
        'middleware' => 'authlog',
    ], function ($router) {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refreshToken']);
        Route::get('/profile', [AuthController::class, 'userProfile']);
        Route::post('/react', [UserIteractionController::class, 'react']);
        Route::post('/search', [UserIteractionController::class, 'search']);
    });

    // Route::group([
    //     'middleware' => 'groups',
    // ], function ($router) {
    
    // });

    Route::any('/{any}', function () {
        return response()->json(['response' => 'Route not found'], 404);
    });
});
