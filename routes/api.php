<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('login',[UserController::class, 'AuthUser']);
Route::post('register',[UserController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() { 
    Route::get('users',[UserController::class, 'getUsers']);
    Route::post('email',[UserController::class, 'EmailToUser']);
    Route::get('auth',[UserController::class, 'getAuthenticatedUser']);
});