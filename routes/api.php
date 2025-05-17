<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/properties', [PropertyController::class, 'index']);
Route::get('/properties/{id}', [PropertyController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    
    //User routes
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/user/properties', [PropertyController::class, 'getUserProperties']);
    Route::post('user/favorite/{id}', [PropertyController::class, 'favorite']);
    Route::get('/user/favorites', [PropertyController::class, 'getFavorites']);

    // Property routes
    Route::post('/properties', [PropertyController::class, 'store']);
    Route::post('/properties/{id}', [PropertyController::class, 'update']);
    Route::delete('/properties/{id}', [PropertyController::class, 'destroy']);

    // Booking routes
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'createBooking']);
    
});