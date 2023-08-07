<?php

use Illuminate\Support\Facades\Route;

Route::resource('indent_v2',\App\Http\Controllers\v2\indent\IndentController::class);
Route::get('barCodeComeItemForIndent',[\App\Http\Controllers\v2\indent\IndentController::class,'barCodeComeItemForIndent']);
Route::post('itemIndentStockWise',[\App\Http\Controllers\v2\indent\IndentController::class,'itemIndentStockWise']);
Route::post('itemShowforIndent',[\App\Http\Controllers\v2\indent\IndentController::class,'itemShowforIndent']);
Route::get('closeIndent/{id}',[\App\Http\Controllers\v2\indent\IndentController::class,'closeIndent']);
Route::get('closeProductRequisition/{id}',[\App\Http\Controllers\Inventory\RequisitionController::class,'closeProductRequisition']);