<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiDemoController;

Route::get('/', [ApiDemoController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// API Demo routes
Route::get('/api-demo', [ApiDemoController::class, 'index'])->name('api-demo');
Route::post('/api-demo/register', [ApiDemoController::class, 'register']);
Route::post('/api-demo/login', [ApiDemoController::class, 'login']);
Route::post('/api-demo/me', [ApiDemoController::class, 'me']);
Route::post('/api-demo/protected', [ApiDemoController::class, 'protected']);
Route::post('/api-demo/refresh', [ApiDemoController::class, 'refresh']);
Route::post('/api-demo/logout', [ApiDemoController::class, 'logout']);
