<?php
use App\Http\Controllers\EventManagement\EvenDueController;
use App\Http\Controllers\EventManagement\EventController;
use App\Http\Controllers\EventManagement\EventInializeController;
use Illuminate\Support\Facades\Route;

Route::prefix('event_management/')->group(function(){
  Route::resource('event',EventController::class);
  Route::get('event_initialize',EventInializeController::class);
  Route::resource('event_due',EvenDueController::class);
  Route::get('/payement_histories_event/{id}',[EvenDueController::class,'payement_histories_event']);
});


