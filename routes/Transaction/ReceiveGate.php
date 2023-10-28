<?php
use App\Http\Controllers\ModuleReceive\ReceiveGateController;
use App\Http\Controllers\ModuleReceive\ReceiveMainStoreController;
use Illuminate\Support\Facades\Route;



Route::prefix('receive')->group(function () {
  Route::resource('/gateReceive',ReceiveGateController::class);
  Route::resource('/mainStoreReceive',ReceiveMainStoreController::class);
});