<?php
use App\Http\Controllers\ModuleReceive\ReceiveGateController;


Route::prefix('receive')->group(function () {
  Route::resource('/gateReceive',ReceiveGateController::class);
});