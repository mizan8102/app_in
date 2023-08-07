<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\report\OrderWiseDailySellController;
use App\Http\Controllers\ProgramQrController;
use App\Http\Controllers\reports\ItemWiseDailySellController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/printshowQr/{id}/{to}', [ProgramQrController::class,'printShow']);
Route::get('/issueRmInvoice/{id}/{to}', [InvoiceController::class,'issueRm']);
require __DIR__.'/report/report.php';