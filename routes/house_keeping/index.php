<?php

use App\Http\Controllers\HouseKeeping\PaymodeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StoreItemMappingController;

Route::prefix('house_keeping')->group(function () {
    Route::resource('uomset', \App\Http\Controllers\HouseKeeping\UOmSetController::class);

    // item store wise mapping
    Route::get('item_store_wise',[\App\Http\Controllers\HouseKeeping\StoreWiseItemController::class,'item_store_wise']);
    Route::get('init_store_mappingGetMasterId/{id}',[\App\Http\Controllers\HouseKeeping\StoreWiseItemController::class,'init_store_mappingGetMasterId']);
    Route::get('init_store_product_type/{id}',[\App\Http\Controllers\HouseKeeping\StoreWiseItemController::class,'init_store_product_type']);
    // 
    Route::get('barCodeComeItemStoreWise',[\App\Http\Controllers\HouseKeeping\StoreWiseItemController::class,'barCodeComeItemStoreWise']);
    Route::post('get_all_item_store_wise',[\App\Http\Controllers\HouseKeeping\StoreWiseItemController::class,'storeWiseItemWithParam']);
    Route::resource('storeWiseItem',\App\Http\Controllers\HouseKeeping\StoreWiseItemController::class);

    Route::resource('paymode',PaymodeController::class);

});
Route::get('itemForStoreMappingByMastergroupSP',[StoreItemMappingController::class,'itemForStoreMappingByMastergroupSP']);
Route::get('GetMappedData',[StoreItemMappingController::class,'GetMappedData']);
