<?php
use App\Http\Controllers\Khajna\ItemReceivedController;
use Illuminate\Support\Facades\Route;

Route::prefix('khajna')->group(function () {
  Route::get('/item-receive_init', [ItemReceivedController::class, 'initData']);
  Route::get('/getVatMonthfinanacialYear/{date}',[ItemReceivedController::class,'getVatMonthfinanacialYear']);
  Route::resource('/item-receive', ItemReceivedController::class);

  
});