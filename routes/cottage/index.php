<?php
use App\Http\Controllers\Cottage\CottageController;
use Illuminate\Support\Facades\Route;

Route::resource('cottage',CottageController::class);
Route::post('/cottage_list',[CottageController::class,'cottage_list']);
Route::post('/cottage_booked_check',[CottageController::class,'cottage_booked_check']);

