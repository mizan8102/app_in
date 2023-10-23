<?php
use App\Http\Controllers\report\ReportApiController;
use Illuminate\Support\Facades\Route;

Route::get('/A_01_generate_itemwise',[ReportApiController::class,'itemWise']);
Route::get('/A_02_order_wise_daily',[ReportApiController::class,'A_02_order_wise_daily']);
Route::get('/issue_details_grid',[ReportApiController::class,'issueDetails']);
Route::get('/indent_report_grid',[ReportApiController::class,'indentReport']);
//issue Return grid
Route::get('/issue_return_grid',[ReportApiController::class,'issueReturnGrid']);
// purchase order summary grid
Route::get('/purchase_order_summary_grid',[ReportApiController::class,'purchasedOrderSummaryGrid']);
// Issue Summary
Route::get('/issue_summary_grid',[ReportApiController::class,'issueSummaryGrid']);
// Receive Summary
Route::get('/receive_summary_grid',[ReportApiController::class,'receiveSummaryGrid']);
// order wise daily sell
Route::get('/order_wise_daily_sell_grid',[ReportApiController::class,'orderWiseDailySellGrid']);
// product requisition summary
Route::get('/product_requisition_summary_grid',[ReportApiController::class,'productRequisitionSummaryGrid']);
// purchase requisition summary
Route::get('/purchase_requisition_summary_grid',[ReportApiController::class,'purchaseRequisitionSummaryGrid']);
// Issue Return summary
Route::get('/issue_return_summary_grid',[ReportApiController::class,'issueReturnSummaryGrid']);