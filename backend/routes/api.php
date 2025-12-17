<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//public routes
Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);

// private routes
Route::apiResource('/users', UserController::class)->middleware('auth:sanctum');
Route::apiResource('/bookings', BookingController::class)->middleware('auth:sanctum');
Route::get('/bookings-conflicts', [BookingController::class, 'conflictReport'])->middleware('auth:sanctum');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
