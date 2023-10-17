<?php
use App\Http\Controllers\ModuleTransfer\TransferOutController;


Route::prefix('transfer')->group(function () {
  Route::resource('/transfer_out',TransferOutController::class);

});