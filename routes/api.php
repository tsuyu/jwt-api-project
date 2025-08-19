<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Protected routes
Route::group(['middleware' => 'auth:api'], function () {
    // Auth related protected routes
    Route::group(['prefix' => 'auth'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });

    // Other protected API routes
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    });
    
    // Example protected route
    Route::get('/protected', function () {
        return response()->json([
            'success' => true,
            'message' => 'This is a protected route',
            'user' => auth()->user(),
            'timestamp' => now()
        ]);
    });

    // You can add more protected routes here
    // Route::apiResource('posts', PostController::class);
    // Route::apiResource('categories', CategoryController::class);
});