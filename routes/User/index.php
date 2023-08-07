<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/user', [AuthController::class, 'getUser']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::resource('/registration', RegistrationController::class);
Route::get('/cs_store_user_wise/{id}',[AuthController::class,'getCurrentStore']);
Route::get('/changeUserStore/{id}/{store}',[AuthController::class,'changeUserStore']);
