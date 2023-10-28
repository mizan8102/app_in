<?php
use App\Http\Controllers\ModuleTransfer\TransferOutController;
use Illuminate\Support\Facades\Route;



Route::prefix('transfer')->group(function () {
  Route::resource('/transfer_out',TransferOutController::class);

});