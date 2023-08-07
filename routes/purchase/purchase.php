<?php
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Controllers\Api\PurchaseOrderDashboard;

  Route::post('/purchaseorderinit', [PurchaseOrderController::class, 'init']);
  Route::resource('/purchaseorder', PurchaseOrderController::class);
  Route::get('/purchaseorderPrint/{id}', [PurchaseOrderController::class, 'ReadOnePurchaseOrder']);
  Route::get('/purchaseOrderOneView/{id}', [PurchaseOrderController::class, 'purchaseOrderOneView']);
  Route::apiResource('/purchaseOrderDashboard', PurchaseOrderDashboard::class);

  // purchase requition for init 
  Route::get('/purchaseOrderRequisitionInit',[PurchaseOrderController::class,'initForPurchaseReq']);
  Route::get('/getPurchaseReqForOrder',[PurchaseOrderController::class,'getPurchaseReqForOrder']);
  Route::get('/status_close_purchase_order/{id}',[PurchaseOrderController::class,'status_close']);

  Route::get('/masterIdToGetTypeAndCategory/{id}',[PurchaseOrderController::class,'masterIdToGetTypeAndCategory']);