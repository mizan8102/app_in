<?php
use App\Http\Controllers\Api\ReceivedRawMaterialDashboard;
use App\Http\Controllers\ReceivedRawMaterialController;

Route::post('/receivedmaterialInit', [ReceivedRawMaterialController::class, 'init']);
    Route::resource('/receiveirowmaterial', ReceivedRawMaterialController::class);
    Route::resource('/receivedDashboard', ReceivedRawMaterialDashboard::class);
    Route::get('/pendingRM', [ReceivedRawMaterialDashboard::class, 'pendingRM']);
    Route::get('/receivedRM', [ReceivedRawMaterialDashboard::class, 'receivedRM']);
