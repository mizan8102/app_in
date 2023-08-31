<?php

use App\Http\Controllers\Api\CardCategoryController;
use App\Http\Controllers\Api\CardTypeController;
use App\Http\Controllers\Api\CashDepositToAccountsController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DuePayController;
use App\Http\Controllers\Api\EntranceTicketController;
use App\Http\Controllers\Api\FloorController;
use App\Http\Controllers\Api\FoodItemsController;
use App\Http\Controllers\Api\GeneralIndentController;
use App\Http\Controllers\Api\GloballyInitializationController;
use App\Http\Controllers\Api\HouseKeepingUOMController;
use App\Http\Controllers\Api\IndentController;
use App\Http\Controllers\Api\IndentDashboard;
use App\Http\Controllers\Api\IndentRequisitionController;
use App\Http\Controllers\Api\InputServiceController;
use App\Http\Controllers\Api\IntemInfoController;
use App\Http\Controllers\Api\IOCPriceDecController;
use App\Http\Controllers\Api\IssueReturnController;
use App\Http\Controllers\Api\IssurRMController;
use App\Http\Controllers\Api\ItemGroupController;
use App\Http\Controllers\Api\ItemSubGroupController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentToSuppliarController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\api\ProductGroupController;
use App\Http\Controllers\Api\ProductMasterGroupController;
use App\Http\Controllers\Api\ProductSubGroupController;
use App\Http\Controllers\Api\ProductTypeController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\ProgramTypesController;
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Controllers\Api\PurchaseOrderDashboard;
use App\Http\Controllers\Api\RCardController;
use App\Http\Controllers\Api\ReceivedRawMaterialDashboard;
use App\Http\Controllers\Api\Report\ProductRequisitionController;
use App\Http\Controllers\Api\Report\ReportIssueController;
use App\Http\Controllers\Api\Report\ReportIssueReturnController;
use App\Http\Controllers\Api\Report\ReportPurchaseOrderController;
use App\Http\Controllers\Api\Report\ReportPurchaseRequisitionController;
use App\Http\Controllers\Api\Report\ReportReceiveController;
use App\Http\Controllers\Api\Report\ReportStoreWiseItemListController;
use App\Http\Controllers\Api\RestaurantTableController;
use App\Http\Controllers\Api\RFloorController;
use App\Http\Controllers\Api\RideTicketController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\StoreItemMappingController;
use App\Http\Controllers\Api\StoreLocationController;
use App\Http\Controllers\Api\SupplierDetailController;
use App\Http\Controllers\Api\SupplierMappingToItemController;
use App\Http\Controllers\Api\SupplierProfileController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\TableTypeController;
use App\Http\Controllers\Api\TransferInController;
use App\Http\Controllers\Api\UOMController;
use App\Http\Controllers\Api\UserRegistrationController;
use App\Http\Controllers\Api\UserRolesController;
use App\Http\Controllers\Api\UserStoreMapppingController;
use App\Http\Controllers\Api\ValueAddedServiceController;
use App\Http\Controllers\Api\VarItemInfoController;
use App\Http\Controllers\Api\VATRegistrationTypesController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Cottage\CottageController;
use App\Http\Controllers\FiscalYearMonth\FiscalyearMonthController;
use App\Http\Controllers\Inventory\RequisitionController;
use App\Http\Controllers\OpeningStock\ExcelController;
use App\Http\Controllers\OpeningStock\OpeningStockController;
use App\Http\Controllers\ProductResourceControllerToGet;
use App\Http\Controllers\ProgramQrController;
use App\Http\Controllers\ReceivedRawMaterialController;
use App\Http\Controllers\report\ReportInitDataController;
use App\Http\Controllers\Requisition\RequisitionDashboardController;
use App\Http\Controllers\Supplier\SupplierMapping;
use App\Http\Controllers\ticketApi\DesktopApp2Controller;
use App\Http\Controllers\ticketApi\DesktopAppController;
use App\Http\Controllers\Transfer\TransferOutController;
use App\Http\Controllers\UserAccess\UserRoleController;
use Illuminate\Support\Facades\Route;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {

    // indent version 2
    require __DIR__ . '/v2/indent.php';
    require __DIR__ . '/User/index.php';
    // house keeping
    require __DIR__ . '/house_keeping/index.php';
    // new event/program part
    require __DIR__.'/event/index.php';
    // cottage
    require __DIR__.'/cottage/index.php';
    // khajna 
    require __DIR__.'/khajna/index.php';

    Route::get('/contact_info_get_by_number/{id}',[CottageController::class,'contact_info_get']);
    //old _ program part
    Route::get('/programinit', [ProgramController::class, 'initialize']);
    Route::get('/storeWiseItem', [ProgramController::class,'storeWiseItem']);
    Route::get('/programMenuget', [ProgramController::class, 'programMenuget']);
    Route::get('/currentprogram', [ProgramController::class, 'currentprogram']);
    Route::resource('/customer', CustomerController::class);
    Route::resource('/program', ProgramController::class);
    Route::get('/hasProgram/{start}/{end}/{floor}', [ProgramController::class, 'hasProgram']);
    Route::get('/todayProgram', [ProgramController::class, 'TodayData']);
    Route::get('/finishedProgram', [ProgramController::class, 'finishedProg']);
    Route::resource('/printqr', ProgramQrController::class);
    Route::get('/printDataApi/{id}', [ProgramQrController::class, 'printDataApi']);

    // indent
    Route::resource('/indent', IndentController::class);
    Route::get('/indentall', [IndentController::class, 'indentall']);
    Route::get('/printinvoiceindent/{id}', [IndentController::class, 'readOnePrint']);
    Route::get('indent_number', [IndentController::class, 'indentNumbers']);
    Route::get('indent_items/{masterId}', [IndentController::class, 'getItems']);

    // generate indent create and management
    Route::resource('/generale-indent', GeneralIndentController::class);

    // indent Dashboard
    Route::resource('/indentdashboard', IndentDashboard::class);
    Route::get('/cardDashboardIndent', [IndentDashboard::class, 'CardData']);
    Route::get('/indexCompleteProgram', [IndentDashboard::class, 'indexCompleteProgram']);

    // indent Requisition  purchase requisition
    Route::resource('/indentrequision', IndentRequisitionController::class);
    Route::post('/saveRequisition', [IndentRequisitionController::class, 'saveRequisition']);
    Route::get('/purchasereq', [IndentRequisitionController::class, 'allPurchaseRequisiton']);
    Route::get('/closePurchaseRequisition/{id}', [IndentRequisitionController::class, 'closePurchaseRequisition']);
    Route::get('/getProductReqForPurchaseRequisition', [IndentRequisitionController::class, 'getProductReqForPurchaseRequisition']);
    Route::post('/mergeProductRequisition', [IndentRequisitionController::class, 'mergeProductRequisition']);
    Route::post('/storePurchaseRequisition', [IndentRequisitionController::class, 'storePurchaseRequisition']);
    Route::get('/showpurchaseone/{id}', [IndentRequisitionController::class, 'ReadOnePurchaseRequisition']);
    Route::apiResource('/requisitionDashboard', RequisitionDashboardController::class);

    Route::get('/pendingRequisition', [RequisitionDashboardController::class, 'pendingRequisition']);
    Route::get('/approvedRequisition', [RequisitionDashboardController::class, 'approvedRequisition']);
    Route::get('/partialRequisition', [RequisitionDashboardController::class, 'partialRequisition']);
    require __DIR__ . '/purchase/purchase.php';
    /**
     * deducated date changed
     */
    Route::post('/changeDeducatedDate', [PurchaseOrderController::class, 'changeDeducatedDate']);
    Route::post('/changeDeducatedRational', [PurchaseOrderController::class, 'changeDeducatedRational']);
    Route::get('/pendingOrder', [PurchaseOrderDashboard::class, 'pendingOrder']);
    Route::get('/pendOrderServer', [PurchaseOrderDashboard::class, 'pendOrderServer']);
    Route::get('/approvedOrder', [PurchaseOrderDashboard::class, 'approvedOrder']);
    // Route::resource('products', ProductController::class);

    //inventory
    Route::get('/initprocatagory', [\App\Http\Controllers\Inventory\IndentController::class, 'initializeData']);
    Route::get('/initprotype/{id}', [\App\Http\Controllers\Inventory\IndentController::class, 'initprotype']);
    Route::get('/initproGroup/{id}', [\App\Http\Controllers\Inventory\IndentController::class, 'initProdVarGroup']);
    Route::get('/initprosubGrp/{id}', [\App\Http\Controllers\Inventory\IndentController::class, 'initprosubGrp']);
    Route::get('/initItemforinventoryIndent/{id}', [\App\Http\Controllers\Inventory\IndentController::class, 'initItemforinventoryIndent']);
    Route::get('/initProgGrpMaster/{id}', [\App\Http\Controllers\Inventory\IndentController::class, 'initProgGrpMaster']);
    Route::resource('/inventoryIndent', \App\Http\Controllers\Inventory\IndentController::class);

    // indent requisition X product requisition
    Route::get('/productRequisition', [RequisitionController::class, 'index']);
    Route::get('/initializeDataInventoryIndentRequisition', [RequisitionController::class, 'initializeData']);
    Route::get('/indentforrequsitioninititalizeData', [RequisitionController::class, 'indentforrequsition']);
    Route::post('/getAllIndentMergeValueForRequisition', [RequisitionController::class, 'getAllIndentMergeValueForRequisition']);
    Route::resource('/requisitionInventory', RequisitionController::class);

    // Received Raw Materials
    Route::get('/receivedmaterialInit', [ReceivedRawMaterialController::class, 'init']);
    Route::get('/receivedmaterialInitSingle/{po_id}', [ReceivedRawMaterialController::class, 'initSingle']);
    Route::resource('/receiveirowmaterial', ReceivedRawMaterialController::class);
    Route::resource('/receivedDashboard', ReceivedRawMaterialDashboard::class);
    Route::get('/pendingRM', [ReceivedRawMaterialDashboard::class, 'pendingRM']);
    Route::get('/receivedRM', [ReceivedRawMaterialDashboard::class, 'receivedRM']);

    // issue rm materials
    Route::get('/initRmIssue', [IssurRMController::class, 'init']);
    Route::get('/initRmIssue/{program_id}', [IssurRMController::class, 'initRmIssueOne']);
    Route::resource('/rmissue', IssurRMController::class);
    Route::get('/updateStatusIssue/{id}', [IssurRMController::class, 'updateStatusIssue']);
    Route::get('/issueReadOne/{id}', [IssurRMController::class, 'ReadOneIssueRm']);
    Route::get('/changePrintQr/{id}/{isPrint}', [ProgramQrController::class, 'isPrintQr']);
    Route::prefix('issue')->group(function () {
        Route::resource('return', IssueReturnController::class);
    });
    Route::prefix('to-account')->group(function () {
        Route::resource('cashDeposit', CashDepositToAccountsController::class);
    });
    Route::prefix('payment-to')->group(function () {
        Route::resource('suppliar', PaymentToSuppliarController::class);
        Route::get('suppliarMerge', [PaymentToSuppliarController::class, 'merge']);
    });

    //IOC Controllers
    Route::resource('/ioc_price_declarations', IOCPriceDecController::class);
    Route::get('/getUomsforIoc/{id}', [IOCPriceDecController::class, 'getUomsforIoc']);
    Route::post('/ioc_price_declarations/with_param', [IOCPriceDecController::class, 'getIOCPriceDeclWithParam']);
    Route::resource('/input_services', InputServiceController::class);
    Route::resource('/value_added_services', ValueAddedServiceController::class);
    Route::post('/get_uom_from_item_uom', [VarItemInfoController::class, 'getUomFromItemUom']);
    Route::get('uomComeGroupWise/{id}', [VarItemInfoController::class, 'getUomGroupWise']);
    Route::resource('/uoms', UOMController::class);
    Route::get('/getuoms', [UOMController::class, 'getUoms']);

    // VAT Registration Types
    Route::resource('/vat_reg_types', VATRegistrationTypesController::class);
    // Floors
    Route::resource('/r_floors', RFloorController::class);
    Route::post('/get_r_floors_with_param', [RFloorController::class, 'getRFloorsWithParam']);
    Route::get('/r_floors/change_status/{id}', [RFloorController::class, 'changeStatus']);
    // Supplier Details
    Route::resource('/supplier_details', SupplierDetailController::class);
    Route::post('/get_supplier_details_with_param', [SupplierDetailController::class, 'getSuppliersWithParam']);
    Route::get('/supplier_details/change_status/{id}', [SupplierDetailController::class, 'changeStatus']);
    // Var Item Info
    Route::resource('/var_items', VarItemInfoController::class);
    Route::post('/get_var_items_param', [VarItemInfoController::class, 'getVarItemsParam']);
    Route::post('/get_all_var_items_param', [VarItemInfoController::class, 'getAllVarItemsParam']);
    Route::get('/var_items/change_status/{id}', [VarItemInfoController::class, 'changeStatus']);
    Route::get('get_item_sub_groups', [VarItemInfoController::class, 'getItemSubGroups']);

    // Var Item Info
    Route::post('/check_program', [ProgramController::class, 'checkProgram']);

    // due pay route
    Route::resource('duepay', DuePayController::class);
    Route::get('/getDuePay', [DuePayController::class, 'duepay']);
    Route::get('/getPaidPay', [DuePayController::class, 'paidPay']);


    /** user access */
    Route::resource('users_menu', \App\Http\Controllers\Menu\MenuController::class);
    Route::resource('/role', UserRoleController::class);
    Route::post('/getAllRole', [UserRoleController::class, 'getAllRole']);


    /** supplier mapping  */
    Route::get('/get-mapping-data', [SupplierMapping::class, 'init']);
    Route::get('/changeStatusMapping/{id}/{status}', [SupplierMapping::class, 'changeStatus']);
    Route::resource('supplier_mapping', SupplierMapping::class);

    /**
     * open stock route
     */
    Route::resource('/openstock', OpeningStockController::class);
    Route::get('/initOpenstockReport', [OpeningStockController::class, 'initOpenstockReport']);
    Route::post('/itemGetWithSubid', [OpeningStockController::class, 'itemGet']);
    Route::get('/initOpeningStock', [OpeningStockController::class, 'init']);
    Route::post('/openStockReport', [OpeningStockController::class, 'openStockReport']);
    Route::prefix('opening/stock')->group(function () {
        Route::post('/import', [ExcelController::class, 'index']);
        Route::get('/download', [ExcelController::class, 'download']);
        Route::get('/export', [ExcelController::class, 'export']);
    });

    /**
     * transfer in out
     */
    Route::get('/pendingIndentList', [TransferOutController::class, 'pendingIndentForTransferOut']);
    Route::get('/pendingIndentOne/{id}', [TransferOutController::class, 'indentNumbers']);
    Route::get('/transferOutIndentComplete', [TransferOutController::class, 'transferOutIndentComplete']);
    Route::resource('/transferOut', TransferOutController::class);
    // TransferIn
    Route::prefix('transferIn')->group(function () {
        Route::get('/', [TransferInController::class, 'index']);
        Route::get('/pendingTout', [TransferInController::class, 'pendingTout']);
        Route::get('/pendingToutOne/{issue_master_id}', [TransferInController::class, 'pendingToutOne']);
        Route::post('/tinStore', [TransferInController::class, 'tinStore']);
        Route::get('/tinOne/{issue_master_id}', [TransferInController::class, 'tinOne']);
    });
    /**
     * Indent Searchable routes
     */
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('product_types', [ProductTypeController::class, 'index']);
    Route::get('item_groups', [ItemGroupController::class, 'index']);
    Route::get('item_sub_groups', [ItemSubGroupController::class, 'index']);
    Route::get('item_informations', [IntemInfoController::class, 'index']);

    // StoreLocation Route
    // Route::get('store_locations', StoreLocationController::class);

    Route::post('indent_kitchen_to_sub_store', [IndentController::class, 'storeKitchenToSubstore']);

    // Restaurant Part APIs
    Route::resource('/restaurant_table', RestaurantTableController::class);
    Route::post('/get_food_menus', [FoodItemsController::class, 'getFoodMenus']);
    Route::post('/get_food_items_param', [FoodItemsController::class, 'getFoodItemsParam']);
    Route::post('/check_user_card_no', [RCardController::class, 'checkUserCardNo']);
    Route::post('/order_place', [OrderController::class, 'store']);
    Route::get('/get_single_order/{id}', [OrderController::class, 'show']);
    Route::get('/get_kitchen_orders', [OrderController::class, 'getKitchenOrders']);
    Route::get('/get_waiter_orders', [OrderController::class, 'getWaiterOrders']);
    Route::get('/get_completed_orders', [OrderController::class, 'getCompletedOrders']);
    Route::post('/order_update', [OrderController::class, 'orderUpdate']);
    Route::post('/update_order_status', [OrderController::class, 'updateOrderStatus']);
    Route::post('/update_process_status', [OrderController::class, 'updateProcessStatus']);
    Route::get('/get_order_statuses', [OrderController::class, 'getOrderStatuses']);
    Route::post('/close_order_operations', [OrderController::class, 'closeOrderOperations']);
    Route::post('/swap_table', [RestaurantTableController::class, 'swapTable']);

    // Individual routes for sperarate category, type, master, group, sub group
    Route::prefix('individual/product/')->group(function () {
        Route::get('/category', [ProductResourceControllerToGet::class, 'category']);
        Route::get('/type', [ProductResourceControllerToGet::class, 'type']);
        Route::get('/masterGroups', [ProductResourceControllerToGet::class, 'masterGroups']);
        Route::get('/groups', [ProductResourceControllerToGet::class, 'groups']);
        Route::get('/subGroups', [ProductResourceControllerToGet::class, 'subGroups']);
        Route::get('/store', [ProductResourceControllerToGet::class, 'store']);
    });
    Route::get('/fiscal-year-month', [FiscalyearMonthController::class, 'index']);
    Route::get('/currency', [FiscalyearMonthController::class, 'currency']);
    Route::prefix('/ticket')->group(function () {
        Route::resource('/service', RideTicketController::class);
        Route::get('/reciept', [RideTicketController::class, 'reciept']);
        Route::resource('/entrance', EntranceTicketController::class);
    });
    Route::prefix('housekeeping')->group(function () {
        Route::get('init_store_item_mapping',[ReportStoreWiseItemListController::class,'init_store_item_mapping']);
        Route::resource('category', ProductCategoryController::class);
        Route::resource('type', ProductTypeController::class);
        Route::resource('mastergroup', ProductMasterGroupController::class);
        Route::resource('group', ProductGroupController::class);
        Route::resource('subgroup', ProductSubGroupController::class);
        Route::resource('product', ProductController::class);
        Route::resource('customer', CustomerController::class);
        Route::resource('cardCategories', CardCategoryController::class);
        Route::resource('cardTypes', CardTypeController::class);
        Route::resource('floor', FloorController::class);
        Route::resource('programTypes', ProgramTypesController::class);
        Route::resource('supplierProfile', SupplierProfileController::class);
        Route::resource('supplierMapping', SupplierMappingToItemController::class);
        Route::resource('userRegistration', UserRegistrationController::class);
        Route::prefix('init')->group(function () {
            Route::get('/category', [GloballyInitializationController::class, 'category']);
            Route::get('/type', [GloballyInitializationController::class, 'type']);
            Route::get('/masterGroups', [GloballyInitializationController::class, 'masterGroups']);
            Route::get('/groups', [GloballyInitializationController::class, 'groups']);
            Route::get('/subGroups', [GloballyInitializationController::class, 'subGroups']);
        });
        Route::resource('user-store-mapping', UserStoreMapppingController::class);
        Route::resource('item-store-mapping', StoreItemMappingController::class);
        Route::get('item-store-mapping/indexNew',[StoreItemMappingController::class,'indexNew']);
        Route::resource('store-location', StoreLocationController::class);
        Route::resource('user-roles', UserRolesController::class);
        Route::resource('rooms', RoomController::class);
        Route::resource('table-type', TableTypeController::class);
        Route::resource('table', TableController::class);

        Route::resource('uom', HouseKeepingUOMController::class);

    });

    //init for report 
    Route::prefix('report')->group(function () {
     Route::get('/store',[ReportInitDataController::class,'Store']);
    });

});

Route::post('/login', [AuthController::class, 'login']);

 require __DIR__.'/report/report_api.php';
Route::prefix('ticket_api')->group(function () {
    //// desktop app
    Route::post('/login', [DesktopAppController::class, 'DesktopAppLogin']);
    Route::get('/get-pos-item-list', [DesktopAppController::class, 'getPOSItemList']);
    Route::get('/get-pos-item-customer-list', [DesktopApp2Controller::class, 'getPOSItemCustomerList']);
    Route::post('/store-pos-sales-transaction', [DesktopAppController::class, 'StorePOSItemTransaction']);
    Route::post('/item-isssue-create-one', [DesktopAppController::class, 'ItemIssueCreateOne']);
    Route::get('/get-daily-sales', [DesktopAppController::class, 'GetDailySales']);
    Route::get('/get-daily-sales-detail', [DesktopAppController::class, 'GetDailyStoreWiseSalesReport']);
    Route::get('/get-entrance-daily-sales', [DesktopAppController::class, 'EntranceGateSellQty']);
    Route::get('/get-item-master-groups-list/{userId}', [DesktopAppController::class, 'GetItemMasterGroupsList']);
    Route::post('/user-sales-summery-slip-printing', [DesktopAppController::class, 'UserSalesSummeryPrinting']);
    Route::get('/get-restaurent-room-wise-table-list/{userId}', [DesktopAppController::class, 'GetRestaurentRoomWiseTableList']);
    Route::get('/check-customer-by-phone-number', [DesktopAppController::class, 'CheckCustomerByPhoneNumber']);
    Route::post('/add-customer-by-phone-number', [DesktopApp2Controller::class, 'StoreCustomerByPhoneNumber']);
    Route::post('/add-customer-by-temp-data', [DesktopApp2Controller::class, 'StoreAllTempCustomer']);
    Route::post('/store-pos-take-order-transaction', [DesktopApp2Controller::class, 'StoreOrderItemTransaction']);
    Route::post('/item-isssue-create-one-take-order', [DesktopApp2Controller::class, 'ItemIssueCreateOneTakeOrder']);
    Route::post('/store-pos-take-order-payment-transaction', [DesktopApp2Controller::class, 'StoreOrderPaymentTransaction']);
    Route::post('/item-isssue-create-one-take-order-payment', [DesktopApp2Controller::class, 'ItemIssueCreateOneTakeOrderPayment']);
    Route::get('/get-runnnig-order-list', [DesktopApp2Controller::class, 'GetRunningOrderList']);
    Route::post('/get-trans-files', [DesktopApp2Controller::class, 'getTransFile']);
});

Route::prefix('ticket_api_v2')->group(function () {
    //// desktop app
    Route::post('/login', [TickingSystemApiV2Controller::class, 'DesktopAppLogin']);
    Route::get('/get-pos-item-list', [TickingSystemApiV2Controller::class, 'getPOSItemList']);
    Route::get('/get-pos-item-customer-list', [TickingSystemApiV2Controller::class, 'getPOSItemCustomerList']);
    Route::post('/store-pos-sales-transaction', [TickingSystemApiV2Controller::class, 'StorePOSItemTransaction']);
    Route::post('/item-isssue-create-one', [TickingSystemApiV2Controller::class, 'ItemIssueCreateOne']);
    Route::get('/get-daily-sales', [TickingSystemApiV2Controller::class, 'GetDailySales']);
    Route::get('/get-daily-sales-detail', [TickingSystemApiV2Controller::class, 'GetDailyStoreWiseSalesReport']);
    Route::get('/get-entrance-daily-sales', [TickingSystemApiV2Controller::class, 'EntranceGateSellQty']);
    Route::get('/get-item-master-groups-list/{userId}', [TickingSystemApiV2Controller::class, 'GetItemMasterGroupsList']);
    Route::post('/user-sales-summery-slip-printing', [TickingSystemApiV2Controller::class, 'UserSalesSummeryPrinting']);
    Route::get('/get-restaurent-room-wise-table-list/{userId}', [TickingSystemApiV2Controller::class, 'GetRestaurentRoomWiseTableList']);
    Route::get('/check-customer-by-phone-number', [TickingSystemApiV2Controller::class, 'CheckCustomerByPhoneNumber']);
    Route::post('/add-customer-by-phone-number', [TickingSystemApiV2Controller::class, 'StoreCustomerByPhoneNumber']);
    Route::post('/add-customer-by-temp-data', [TickingSystemApiV2Controller::class, 'StoreAllTempCustomer']);
    Route::post('/store-pos-take-order-transaction', [TickingSystemApiV2Controller::class, 'StoreOrderItemTransaction']);
    Route::post('/item-isssue-create-one-take-order', [TickingSystemApiV2Controller::class, 'ItemIssueCreateOneTakeOrder']);
    Route::post('/store-pos-take-order-payment-transaction', [TickingSystemApiV2Controller::class, 'StoreOrderPaymentTransaction']);
    Route::post('/item-isssue-create-one-take-order-payment', [TickingSystemApiV2Controller::class, 'ItemIssueCreateOneTakeOrderPayment']);
    Route::get('/get-runnnig-order-list', [TickingSystemApiV2Controller::class, 'GetRunningOrderList']);
    Route::post('/get-trans-files', [TickingSystemApiV2Controller::class, 'getTransFile']);
});

Route::prefix('report')->group(function () {
    Route::prefix('productRequisition')->group(function () {
        Route::get('getProductRequisitionNo', [ProductRequisitionController::class, 'getProductRequisitionNo']);
        Route::get('getProductRequisitionByNo', [ProductRequisitionController::class, 'getProductRequisitionByNo']);
        Route::get('report/getProductRequisitionPDFByNo', [ProductRequisitionController::class, 'getProductRequisitionByNoPDF']);
        Route::get('productRequisitionSummaryByDate', [ProductRequisitionController::class, 'productRequisitionSummaryByDate']);
        Route::get('productRequisitionSummaryByDatePDF', [ProductRequisitionController::class, 'productRequisitionSummaryByDatePDF']);
    });
    Route::prefix('purchaseRequisition')->group(function () {
        Route::get('getPurchaseRequisitionNo', [ReportPurchaseRequisitionController::class, 'getPurchaseRequisitionNo']);
        Route::get('getPurchaseRequisitionByNo', [ReportPurchaseRequisitionController::class, 'getPurchaseRequisitionByNo']);
        Route::get('report/getPurchaseRequisitionPDFByNo', [ReportPurchaseRequisitionController::class, 'getPurchaseRequisitionPDFByNo']);
        Route::get('purchaseRequisitionSummaryByDate', [ReportPurchaseRequisitionController::class, 'purchaseRequisitionSummaryByDate']);
        Route::get('purchaseRequisitionSummaryByDatePDF', [ReportPurchaseRequisitionController::class, 'purchaseRequisitionSummaryByDatePDF']);
    });
    Route::prefix('purchaseOrderReport')->group(function () {
        Route::get('getPOList', [ReportPurchaseOrderController::class, 'getPOList']);
        Route::get('getPOById', [ReportPurchaseOrderController::class, 'getPOById']);
        Route::get('getPOByIdPDF', [ReportPurchaseOrderController::class, 'getPOByIdPDF']);
        Route::get('getPOSummaryByDate', [ReportPurchaseOrderController::class, 'getPOSummaryByDate']);
        Route::get('getPOSummaryByDatePDF', [ReportPurchaseOrderController::class, 'getPOSummaryByDatePDF']);
    });
    Route::prefix('issueReport')->group(function () {
        Route::get('getIssueNo', [ReportIssueController::class, 'getIssueNo']);
        Route::get('getIssueDetailsByNo', [ReportIssueController::class, 'getIssueDetailsByNo']);
        Route::get('getIssueDetailsByNoByNoPDF', [ReportIssueController::class, 'getIssueDetailsByNoByNoPDF']);
        Route::get('getIssueSummaryByDate', [ReportIssueController::class, 'getIssueSummaryByDate']);
        Route::get('getIssueSummaryByDatePDF', [ReportIssueController::class, 'getIssueSummaryByDatePDF']);
    });
    Route::prefix('recvReport')->group(function () {
        Route::get('getGRNNo', [ReportReceiveController::class, 'getGRNNo']);
        Route::get('getRecvByGRNNo', [ReportReceiveController::class, 'getRecvByGRNNo']);
        Route::get('getRecvByGRNNoPDF', [ReportReceiveController::class, 'getRecvByGRNNoPDF']);
        Route::get('getRecvSummaryByDate', [ReportReceiveController::class, 'getRecvSummaryByDate']);
        Route::get('getRecvSummaryByDatePDF', [ReportReceiveController::class, 'getRecvSummaryByDatePDF']);
    });
    Route::prefix('issueReturn')->group(function () {
        Route::get('index', [ReportIssueReturnController::class, 'index']);
        Route::get('getById', [ReportIssueReturnController::class, 'getById']);
        Route::get('getByIdPDF', [ReportIssueReturnController::class, 'getByIdPDF']);
        Route::get('getByProgram', [ReportIssueReturnController::class, 'getByProgram']);
        Route::get('getByProgramPDF', [ReportIssueReturnController::class, 'getByProgramPDF']);
        Route::get('getSummary', [ReportIssueReturnController::class, 'getSummary']);
        Route::get('getSummaryPDF', [ReportIssueReturnController::class, 'getSummaryPDF']);
    });
    Route::prefix('storeWiseItem')->group(function(){
        Route::get('/index',[ReportStoreWiseItemListController::class,'index']);
        Route::get('/getById',[ReportStoreWiseItemListController::class,'getById']);
        Route::get('/getByIdPDF',[ReportStoreWiseItemListController::class,'getByIdPDF']);
    });
});
