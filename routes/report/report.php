<?php

use App\Http\Controllers\Api\Report\ReportStoreWiseItemListController;
use App\Http\Controllers\report\DailySellsSummaryController;
use App\Http\Controllers\report\ProgramIndentController;
use App\Models\report\DailySalesSummery;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\report\MRController;
use App\Http\Controllers\report\ItemController;
use App\Http\Controllers\report\IssueController;
use App\Http\Controllers\report\IndentController;
use App\Http\Controllers\report\ProductController;
use App\Http\Controllers\report\ReceiveController;
use App\Http\Controllers\report\PurchaseController;
use App\Http\Controllers\report\ReceiveReportController;
use App\Http\Controllers\report\WaiterWiseSellController;
use App\Http\Controllers\report\KitchenWiseSellController;
use App\Http\Controllers\report\CommercialInvoiceController;
use App\Http\Controllers\report\ItemWiseDailySellController;
use App\Http\Controllers\report\CostingConsumptionController;
use App\Http\Controllers\report\IssueReturnProgramController;
use App\Http\Controllers\report\OrderWiseDailySellController;
use App\Http\Controllers\report\OpeningStockSummaryController;
use App\Http\Controllers\report\WaiterWiseDailySellController;
use App\Http\Controllers\Api\Report\ProductRequisitionController;
use App\Http\Controllers\report\CostingConsumptionSummaryController;
use App\Http\Controllers\report\ProductTypeWiseItemReportController;

Route::prefix('report')->group(function () {

    //antu
    Route::get('item-wise-daily-sell', [ItemWiseDailySellController::class, 'itemWiseDailySell']);
    Route::get('order-wise-daily-sell', [OrderWiseDailySellController::class, 'orderWiseDailySell']);
    Route::get('waiter-wise-sell', [WaiterWiseSellController::class, 'waiterWiseSell']);
    Route::get('waiter-wise-daily-sell', [WaiterWiseDailySellController::class, 'waiterWisedailySell']);
    Route::get('kitchen-wise-sell', [KitchenWiseSellController::class, 'kitchenWiseSell']);
    Route::get('commercial-invoice', [CommercialInvoiceController::class, 'programManagement']);
    Route::get('commercial-invoice-quotations', [CommercialInvoiceController::class, 'quotations']);
    Route::get('costing-consumption', [CostingConsumptionController::class, 'costingConsumption']);
    Route::get('costing-consumption-summary', [CostingConsumptionSummaryController::class, 'costingConsumptionSummary']);
    Route::get('opening-stock-summary', [OpeningStockSummaryController::class, 'openingStockSummary']);
    Route::get('receive-report', [ReceiveReportController::class, 'receiveReport']);
    Route::get('issue-retun-for-program', [IssueReturnProgramController::class, 'issueReturnProgram']);


    // 
    Route::get('programIndentReport',[ProgramIndentController::class,'indentReportTwoPdf']);

    // sumon

    // Money Receipt
    Route::get('/money-receipt', [MRController::class, 'mReceipt']);
    // Indent
    Route::get('/indent-report', [IndentController::class, 'indentReport']);
    Route::get('/indent-summery', [IndentController::class, 'indentSummery']);
    // product requisition
    Route::get('/product-requisition', [ProductController::class, 'productRequisition']);
    Route::get('/requisition-summery', [ProductController::class, 'requisitionSummery']);
    //purchase Requisition
    Route::get('/purchase-requisition', [PurchaseController::class, 'purchaseRequisition']);
    Route::get('/purchase-requisition-summery', [PurchaseController::class, 'purchaseRequisitionSummery']);
    Route::get('/purchase-order', [PurchaseController::class, 'purchaseOrder']);
    Route::get('/purchase-order-summery', [PurchaseController::class, 'purchaseOrderSummery']);
    //issue
    Route::get('/issue-details', [IssueController::class, 'issueDetails']);
    Route::get('/issue-summery', [IssueController::class, 'issueSummery']);
    Route::get('/issue-return', [IssueController::class, 'issueReturn']);
    //Receive
    Route::get('/receive-summery', [ReceiveController::class, 'receiveSummery']);
    //item
    Route::get('/item-stock-summery', [ItemController::class, 'itemStockSummery']);
    Route::get('/Report_C_02A_01_opening_balance_info', [OpeningStockSummaryController::class, 'openingBalanceReceive']);
    Route::get('/C_02A_01', [OpeningStockSummaryController::class, 'openingStockSummary']);
    Route::get('/daily_sales_summery_cash_receive',[\App\Http\Controllers\report\CashReceiveReportController::class,'dailySellCashReceive']);
    Route::get('/daily_sales_summery_store_wise',[\App\Http\Controllers\report\CashReceiveReportController::class,'daily_sells_summary']);

    Route::get('/store_wise_item_list',[ReportStoreWiseItemListController::class,'store_wise_item_list']);

    Route::get('/Report_Z_01_store_wise_item_list', [DailySellsSummaryController::class, 'dailyMismatchSales']);

    // consting pdf IOC
    Route::get('/costingConsumptionPDF', [CostingConsumptionController::class, 'costingConsumptionPDF']);

    Route::get('/C_01B-pdf', [CostingConsumptionSummaryController::class, 'costingConsumptionSummaryPdf']);
    Route::get('/C_01C', [DailySellsSummaryController::class, 'pdf']);


    Route::get('/C_01D',[DailySellsSummaryController::class,'consumptionPdf']);
});

//// New Report by Lokman Hossain //////////////////////////////////////////////////////////////////
Route::get('rm-item-master-group-wise', [ProductTypeWiseItemReportController::class, 'RMListMasterGroupWise']);
Route::get('item-list-by-master-group', [ProductTypeWiseItemReportController::class, 'ItemListByMasterGroup']);