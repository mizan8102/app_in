<?php
use App\Http\Controllers\report\ReportApiController;
use Illuminate\Support\Facades\Route;

Route::get('/A_01_generate_itemwise',[ReportApiController::class,'itemWise']);
Route::get('/A_02_order_wise_daily',[ReportApiController::class,'A_02_order_wise_daily']);