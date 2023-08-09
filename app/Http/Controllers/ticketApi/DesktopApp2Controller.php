<?php

namespace App\Http\Controllers\ticketApi;

use App\Helpers\Helper;
use App\Http\Resources\UserResource;
use Exception;
use Throwable;
use Carbon\Carbon;
use App\Models\SvUOM;
use App\Models\Currency;
use App\Models\SubGroup;
use App\Models\RecvChild;
use App\Models\IssueChild;
use App\Models\RecvMaster;
use App\Models\ProductType;
use App\Models\VarItemInfo;
use App\Models\ProductGroup;
use Illuminate\Http\Request;
use App\Models\ItemStockChild;
use App\Models\ItemMasterGroup;
use App\Models\ItemMasterModel;
use App\Models\ItemStockMaster;
use App\Models\ProductCatagory;
use App\Models\SupplierMapping;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\TransactionSourceType;
use App\Models\CsCompanyStoreLocation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Hash;

class DesktopApp2Controller extends Controller
{


    public function getPOSItemCustomerList(Request $r){

        $itemData = $dataArr =  DB::select('CALL GetPOSFGItemList("'.$r->user_id.'")');
        foreach($dataArr as $i=>$item){
            $item_price =  DB::table('trns03b_issue_child')
            ->leftJoin('trns03a_issue_master', 'trns03b_issue_child.issue_master_id','=','trns03a_issue_master.id')
            ->where('trns03b_issue_child.item_info_id',$item->item_info_id)
            ->where('trns03b_issue_child.created_by',$r->user_id)
            ->whereDate('trns03a_issue_master.issue_date',Date('Y-m-d'))
            ->sum('trns03b_issue_child.item_value_tran_curr');
            $itemData[$i]->item_price = floatVal($item_price);

            $item_qty =  DB::table('trns03b_issue_child')
            ->leftJoin('trns03a_issue_master', 'trns03b_issue_child.issue_master_id','=','trns03a_issue_master.id')
            ->where('trns03b_issue_child.item_info_id',$item->item_info_id)
            ->where('trns03b_issue_child.created_by',$r->user_id)
            ->whereDate('trns03a_issue_master.issue_date',Date('Y-m-d'))
            ->sum('trns03b_issue_child.issue_qty');
            $itemData[$i]->item_qty = intVal($item_qty);
            $itemData[$i]->cart_qty = 0;
        }
        $data['item_list'] = $itemData;

        $dataArr = Collect($itemData);
        $subGroupsArr = $dataArr->groupBy('subGroupId')->sortBy('subGroupName');
        $masterGroupsArr = $dataArr->groupBy('masterGroupId')->sortBy('masterGroup');
        $groupsArr = $dataArr->groupBy('GroupId')->sortBy('masterGroup');
        $masterGroupResult = [];
        $groupResult = [];
        $subGroupResult = [];

        

        $s = 0;
        foreach($masterGroupsArr as $masterGroupId=>$row){
            $masterGroupResult[$s]['masterGroupId'] =  $row[0]->masterGroupId;
            $masterGroupResult[$s]['masterGroup'] =  $row[0]->masterGroup;
            $masterGroupResult[$s]['masterGroupBn'] =  $row[0]->masterGroupBn;
            $s++;
        }

        $l = 0;
        foreach($groupsArr as $GroupId=>$row){
            $groupResult[$l]['masterGroupId'] =  $row[0]->masterGroupId;
            $groupResult[$l]['masterGroup'] =  $row[0]->masterGroup;
            $groupResult[$l]['masterGroupBn'] =  $row[0]->masterGroupBn;
            $groupResult[$l]['groupId'] =  $row[0]->GroupId;
            $groupResult[$l]['groupName'] =  $row[0]->GroupName;
            $groupResult[$l]['groupNameBn'] =  $row[0]->GroupName;
            $l++;
        }

        $k = 0;
        foreach($subGroupsArr as $subGroupId=>$row){
            $subGroupResult[$k]['masterGroupId'] =  $row[0]->masterGroupId;
            $subGroupResult[$k]['masterGroup'] =  $row[0]->masterGroup;
            $subGroupResult[$k]['masterGroupBn'] =  $row[0]->masterGroupBn;
            $subGroupResult[$k]['groupId'] =  $row[0]->GroupId;
            $subGroupResult[$k]['groupName'] =  $row[0]->GroupName;
            $subGroupResult[$k]['groupNameBn'] =  $row[0]->GroupName;
            $subGroupResult[$k]['subGroupId'] =  $row[0]->subGroupId;
            $subGroupResult[$k]['subGroupName'] =  $row[0]->subGroupName;
            $subGroupResult[$k]['subGroupNameBn'] =  $row[0]->subGroupNameBn;
            $k++;
        }
        
        $layoutList = [
            'masterGroups'=>$masterGroupResult,
            'groups'=>$groupResult,
            'subGroups'=>$subGroupResult,
        ];
        $data['item_layout_list'] = $layoutList;

        $custData = $custRow = DB::table('cs_customer_contact_info')
        ->leftJoin('cs_customer_details','cs_customer_contact_info.customer_id','=','cs_customer_details.id')
        ->select('cs_customer_details.id','cs_customer_details.customer_name','cs_customer_details.customer_name_bn','cs_customer_contact_info.phone','cs_customer_details.address')
        ->get();

        foreach($custData as $k=>$row){
            $custRow[$k]->phone = '0'.ltrim($row->phone,'0');
            $custRow[$k]->isTemp = 0;
        }
        $data['customer_list'] = $custRow;

        return $data;
    }

    public function StoreAllTempCustomer(Request $r){
        $dataArr = $r->cust_data;
        $data = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $dataArr),true);
        $isInvalidStore = false;
        $arr = [];
        foreach($data as $cust){
            $custData = DB::table('cs_customer_contact_info')->where('phone',$cust['phone'])->first();
            if(!$custData){
                $custDetails = [
                    'company_id'=>1,
                    'vat_reg_id'=>1,
                    'customer_name'=>$cust['customer_name'],
                    'customer_name_bn'=>$cust['customer_name_bn'],
                    'email_address'=>'demo@email.com',
                    'address'=>$cust['address'],
                    'address_bn'=>$cust['address'],
                ];
                $custID = DB::table('cs_customer_details')->insertGetId($custDetails);
                if($custID){
                    $custContact = [
                        'customer_id'=>$custID,
                        'phone'=>$cust['phone'],
                        'country'=>1,
                        'email_address'=>'demo@email.com',
                    ];
                    $custConID = DB::table('cs_customer_contact_info')->insertGetId($custContact);
                    
                }else{
                    $isInvalidStore = true;
                }
            }
        }

        $custData = $custRow = DB::table('cs_customer_contact_info')
        ->leftJoin('cs_customer_details','cs_customer_contact_info.customer_id','=','cs_customer_details.id')
        ->select('cs_customer_details.id','cs_customer_details.customer_name','cs_customer_details.customer_name_bn','cs_customer_contact_info.phone','cs_customer_details.address')
        ->get();

        foreach($custData as $k=>$row){
            $custRow[$k]->phone = '0'.ltrim($row->phone,'0');
            $custRow[$k]->isTemp = 0;
        }
        $result['status'] = true;
        $result['customer_list'] = $custRow;
        return $result;
    }

    public function StoreCustomerByPhoneNumber(Request $r){
        $custName = $r->customer_name;
        $custNameBn = $r->customer_name_bn;
        $custPhone = $r->customer_phone;
        $custAddress = $r->customer_address;
        $userId = $r->user_id;
        $custDetails = [
            'company_id'=>1,
            'vat_reg_id'=>1,
            'customer_name'=>$custName,
            'customer_name_bn'=>$custNameBn,
            'email_address'=>'demo@email.com',
            'address'=>$custAddress,
            'address_bn'=>$custAddress,
        ];
        
        DB::beginTransaction();
        try{
            
            $custID = DB::table('cs_customer_details')->insertGetId($custDetails);
            $custContact = [
                'customer_id'=>$custID,
                'phone'=>$custPhone,
                'country'=>1,
                'email_address'=>'demo@email.com',
            ];
            $custID = DB::table('cs_customer_contact_info')->insertGetId($custContact);
            DB::commit();
            $resData = DB::table('cs_customer_contact_info')
            ->leftJoin('cs_customer_details','cs_customer_contact_info.customer_id','=','cs_customer_details.id')
            ->where('cs_customer_contact_info.phone',$custPhone)
            ->select('cs_customer_details.id','cs_customer_details.customer_name','cs_customer_details.customer_name_bn','cs_customer_contact_info.phone')
            ->first();
            $result = [];
            if($resData){
                $result['resp_status'] = true;
                $result['customerId'] = $resData->id;
                $result['customerName'] = $resData->customer_name;
                $result['customerNameBn'] = $resData->customer_name_bn;
                $result['customerPhone'] = $resData->phone;
            }else{
                $result['resp_status'] = false;
                $result['customerId'] = '';
                $result['customerName'] = '';
                $result['customerNameBn'] = '';
                $result['customerPhone'] = '';
            }
            return $result;
        }
        catch (\Exception $e) {
            DB::rollback();
            return [
                'resp_status' => false,
                'resp_msg' => 'inserted failed',
                'resp_err' => $e->getMessage(),
                'customerId' => '',
                'customerName' => '',
                'customerNameBn' => '',
                'customerPhone' => '',
            ];
        }

    }

    public function StoreOrderItemTransaction(Request $r){
        // return $r;
        date_default_timezone_set('Asia/Dhaka');
        
        $cartDataJson = $r->cart_data;
        $cartDataJson = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $cartDataJson),true);
        $id = $cartDataJson['id'];
        $transTime = $cartDataJson['trans_time'];
        $userId = $cartDataJson['user_id'];
        $appVersion = $r->app_version;

        $issueTimeLog = strtotime($transTime);
        DB::table('trans_issues_init_log')->insert([
            'user_id'=>$userId,
            'issue_ref'=>$id,
            'guard'=>$issueTimeLog,
            'version'=>$appVersion,
            'issue_request_data'=>$r->cart_data,
            'created_at'=>Date('Y-m-d H:i:s')
        ]);
        // if($userId != '343'){
        //     return [
        //         'status'=>false,
        //         'reason'=>'working on server',
        //         'issue_master_id'=>'',
        //         'itemstock_master_id'=>'',
        //     ];
        // }

        $userData = DB::table('users')->where('id',$userId)->first();
        if(!$userData){
            return [
                'status'=>false,
                'message'=>'User not found',
            ];
        }

        
        $userStoreId = $cartDataJson['user_store_id'];
        $onlineOrderId = $cartDataJson['onlineOrderId'];
        $waiterId = $cartDataJson['waiterId'];
        $isUpdate = $cartDataJson['isUpdate'];
        $orderType = $cartDataJson['orderType'];
        $orderStatus = $cartDataJson['orderStatus'];
        $orderStatus = $cartDataJson['orderStatus'];
        $custAddress = $cartDataJson['customerName'];
        $customerName = $cartDataJson['customerName'];
        $customerPhone = '0'.ltrim($cartDataJson['customerPhone'],'0');
        $custData = DB::table('cs_customer_contact_info')->where('phone',$customerPhone)->first();
        
        if($custData){
            $customerId = $custData->customer_id;
        }else{
            $custDetails = [
                'company_id'=>1,
                'vat_reg_id'=>1,
                'customer_name'=>$customerName,
                'customer_name_bn'=>$customerName,
                'email_address'=>'demo@email.com',
                'address'=>$custAddress,
                'address_bn'=>$custAddress,
            ];
            $custID = DB::table('cs_customer_details')->insertGetId($custDetails);
            
            if($custID){
                $custContact = [
                    'customer_id'=>$custID,
                    'phone'=>$customerPhone,
                    'country'=>1,
                    'email_address'=>'demo@email.com',
                ];
                $custConID = DB::table('cs_customer_contact_info')->insertGetId($custContact);
                $customerId = $custID;
            }else{
                $customerId = '';
                DB::table('trans_issues_error_log')->insert([
                    'user_id'=>$userId,
                    'guard'=>$issueTimeLog,
                    'issue_ref'=>$id,
                    'issue_response'=>'Customer Not Found',
                    'created_at'=>Date('Y-m-d H:i:s')
                ]);
                return [
                    'status'=>false,
                    'reason'=>'Customer Not Found',
                    'issue_master_id'=>'',
                    'itemstock_master_id'=>'',
                ];
            }
        }
        // return $customerPhone;
        $totalVatPercent = 0;
        $totalVatAmount = 0;
        $totalDiscount = 0;
        $totalSellAmount = 720;
        $itemIdArr = json_decode($cartDataJson['item_id']);
        $itemNameArr = json_decode($cartDataJson['item_name']);
        $itemRateArr = json_decode($cartDataJson['item_rate']);
        $itemQtyArr = json_decode($cartDataJson['item_qty']);
        $itemAmountArr = json_decode($cartDataJson['item_amount']);
        $defVatRateIdArr = json_decode($cartDataJson['def_vat_rate_id']);
        $hsCodeIdArr = json_decode($cartDataJson['hs_code_id']);
        $tableNoArr = json_decode($cartDataJson['tableId']);
        $isSuppArr = json_decode($cartDataJson['isSupp']);
        $totalMasterPrice = array_sum($itemAmountArr);
        $i = 0;
        $printDataArr = [];

        $tableDataArr = [];
        foreach($tableNoArr as $t=>$table){
            $tableData = DB::table('var_restaurant_table')->select('room_id','id','floor_id')->where('id',$table)->first();
            if($tableData){
                $tableDataArr[$t]['store_id'] = $userStoreId;
                $tableDataArr[$t]['floor_id']=$tableData->floor_id;
                $tableDataArr[$t]['room_id']=$tableData->room_id;
                $tableDataArr[$t]['table_id']=$tableData->id;
                $tableDataArr[$t]['is_active']=1;
                $tableDataArr[$t]['created_by']=$userId;
            }
            
        }

        for($k=0;count($itemIdArr) > $k;$k++){
            if($k == 0){
                $resultArr['onlineOrderId'] = $onlineOrderId;
                $resultArr['is_update'] = $isUpdate;
                $resultArr['company_id'] = $userData->company_id;
                $resultArr['branch_id'] = $userData->branch_id;
                $resultArr['store_id'] = $userStoreId;
                $resultArr['order_type_id'] = $orderType;
                $resultArr['trans_src_type_id'] = 2;
                $resultArr['tran_type_id'] = 2;
                $resultArr['tran_sub_type_id'] = 4;
                $resultArr['prod_type_id'] = 3;
                $resultArr['waiter_id'] = $waiterId;
                $resultArr['customer_id'] = $customerId;
                $resultArr['customer_phone'] = $customerPhone;
                $resultArr['order_status'] = $orderStatus;
                $resultArr['id'] = $id;
                $resultArr['currency_id'] = 1;
                $resultArr['excg_rate'] = 1;
                $resultArr['log_time'] = $issueTimeLog;
                $resultArr['issue_date'] = Date('Y-m-d H:i:s',strtotime($transTime));
                $resultArr['monthly_proc_status'] = 0;
                $resultArr['vat_challan_date'] = Date('Y-m-d H:i:s');
                $resultArr['total_sd_amount'] = 0;
                $resultArr['fiscal_year'] = 1;
                $resultArr['vat_month'] = 1;
                $resultArr['total_vat_percent'] = $totalVatPercent;
                $resultArr['total_vat_amnt'] = $totalVatAmount;
                $resultArr['total_discount'] = $totalDiscount;
                $resultArr['total_issue_amount_cc'] = $totalMasterPrice-$totalDiscount;
                $resultArr['total_amount_lc'] = $totalMasterPrice-$totalDiscount;
                $resultArr['reg_status'] = 1;
                $resultArr['created_by'] = $userId;
                $resultArr['req_no'] = $id;
                $resultArr['created_at'] = Date('Y-m-d H:i:s');
                $resultArr['tableList'] = $tableDataArr;
                $resultArr['item_row'] = [];
            }
            $defVatRateId = $defVatRateIdArr[$k];
            $hsCodeId = $hsCodeIdArr[$k];
            $index = $i;
            $vatData = DB::table('var_vat_structure_rates AS vsr')
                        ->where('hs_code_id',$hsCodeId)
                        ->where('vat_rate_type_id',$defVatRateId)
                        ->select('vat','sd')
                        ->orderby('id','desc')
                        ->first();

            if($vatData){
                if($vatData->sd == ''){
                    $sd =0;
                }
                else{
                    $sd = $vatData->sd;
                }
                if ($vatData->vat == ''){
                    $vat =0;
                }
                else{
                    $vat = $vatData->vat;
                }
            }else{
                $sd =0;
                $vat =0;
            }
            $itemId = $itemIdArr[$k];
            $totalAmount = $itemAmountArr[$k];
            $itemRate = $itemRateArr[$k];
            $itemQty = $itemQtyArr[$k];
            $itemName = $itemNameArr[$k];
            $isSupp = $isSuppArr[$k];
            $sdAmount = ($totalAmount*$sd)/100;
            $totalWithSd = $totalAmount+$sdAmount;
            $vatAmount = ($totalWithSd*$vat)/100;
            $grandTotal = $totalWithSd+$vatAmount;
            $resultArr['item_row'][$i]['item_information_id'] = $itemId;
            $resultArr['item_row'][$i]['uom_id'] = 1;
            $resultArr['item_row'][$i]['uom_short_code'] = 'Pcs';
            $resultArr['item_row'][$i]['relative_factor'] = 1;
            $resultArr['item_row'][$i]['sales_rel_fact'] = 1;
            $resultArr['item_row'][$i]['issue_qty'] = $itemQty;
            $resultArr['item_row'][$i]['issue_rate'] = $itemRate;
            $resultArr['item_row'][$i]['item_value_tran_curr'] = $totalAmount;
            $resultArr['item_row'][$i]['item_value_local_curr'] = $totalAmount;
            $resultArr['item_row'][$i]['is_fixed_rate'] = 0;
            $resultArr['item_row'][$i]['is_supp'] = $isSupp;
            $resultArr['item_row'][$i]['sd_percent'] = $sd;
            $resultArr['item_row'][$i]['sd_amount'] = $sdAmount;
            $resultArr['item_row'][$i]['vat_percent'] = $vat;
            $resultArr['item_row'][$i]['vat_amount'] = $vatAmount;
            $resultArr['item_row'][$i]['vat_rate_type_id'] = 5;
            $resultArr['item_row'][$i]['total_amount_local_curr'] = $grandTotal;
            $resultArr['item_row'][$i]['created_by'] = $userId;
            $i++;
        }
        // return $resultArr;
        $isStore = Http::post("http://159.223.67.50/chiklee_test_server/chiklee_api/public/api/ticket_api/item-isssue-create-one-take-order",$resultArr)->json();
        return [
            'status'=>$isStore['status'],
            'reason'=>$isStore['reason'],
            'issue_master_id'=>$isStore['issue_master_id'],
            'itemstock_master_id'=>$isStore['itemstock_master_id'],
            // 'print_data'=>$printDataArr,
        ];
    }

    public function ItemIssueCreateOneTakeOrder(Request $r){
        date_default_timezone_set('Asia/Dhaka');
        $isLog = false;

        $total_sd_amount=$r->total_sd_amount;
        if ($total_sd_amount == '')
        {
            $total_sd_amount=0;
        }
        $remarks=$r->remark;
        if ($remarks == '')
        {
            $remarks = $r->id;
        }
        $reg_status = 1;
        if($r->reg_status == ''){
            $reg_status = 0;
        }
        $monthly_proc_status = 1;
        if($r->monthly_proc_status == ''){
            $monthly_proc_status = 0;
        }
        if($r->onlineOrderId == '' && $r->is_update == '0'){
            $isDuplicate = DB::table('trans_issues_log')->where('user_id',$r->created_by)->where('issue_ref',$r->id)->where('guard',$r->log_time)->first();
            if($isDuplicate){
                DB::table('trans_issues_error_log')->insert([
                    'user_id'=>$r->created_by,
                    'guard'=>$r->log_time,
                    'issue_ref'=>$r->id,
                    'issue_response'=>'('.$isDuplicate->id.') Data Already Exist',
                    'created_at'=>Date('Y-m-d H:i:s')
                ]);
                return [
                    'status'=>true,
                    'reason'=>'Data Already Exist',
                    'issue_master_id'=>'',
                    'itemstock_master_id'=>'',
                ];
                $isLog = true;
            }else{
               $existingOrder =  DB::table('trns00g_order_master')->where('id',$r->onlineOrderId)->first();
               if($existingOrder){
                DB::table('trans_issues_error_log')->insert([
                    'user_id'=>$r->created_by,
                    'guard'=>$r->log_time,
                    'issue_ref'=>$r->id,
                    'issue_response'=>'Order ID Exist . Order ID :'.$r->onlineOrderId,
                    'created_at'=>Date('Y-m-d H:i:s')
                ]);
                return [
                    'status'=>true,
                    'reason'=>'Order ID Exist',
                    'issue_master_id'=>'',
                    'itemstock_master_id'=>'',
                ];
               }
               
            }

            DB::beginTransaction();
            try{
                $issueDate = Date('Y-m-d H:i:s',strtotime($r->issue_date));
    
                // $issueMasterData=[
                //     'tran_source_type_id'=>$r->trans_src_type_id,
                //     'tran_type_id'=>$r->tran_type_id,
                //     'tran_sub_type_id'=>$r->tran_sub_type_id,
                //     'prod_type_id'=>$r->prod_type_id,
                //     'company_id'=>$r->company_id,
                //     'branch_id'=>$r->branch_id,
                //     'store_id'=>$r->store_id,
                //     'currency_id'=>$r->currency_id,
                //     'excg_rate'=>$r->excg_rate,
                //     'customer_id'=>$r->customer_id,
                //     'reg_status'=>$reg_status,
                //     'issue_date'=>$issueDate,
                //     'fiscal_year_id'=>$r->fiscal_year,
                //     'vat_month_id'=>$r->vat_month,
                //     'challan_type'=>$r->challan_type,
                //     'challan_number'=>$r->vat_challan_number,
                //     'challan_number_bn'=>$r->vat_challan_number,
                //     'challan_date'=>$issueDate,
                //     'total_sd_amnt'=>$total_sd_amount,
                //     'total_vat_amnt'=>$r->total_vat_amnt,
                //     'total_issue_amount'=>$r->total_issue_amount_cc,
                //     'total_issue_amt_local_curr'=>$r->total_amount_lc,
                //     'remarks'=>$remarks,
                //     'remarks_bn'=>$remarks,
                //     'monthly_proc_status'=>$monthly_proc_status,
                //     'created_by'=>$r->created_by,
                //     'updated_by'=>$r->updated_by,
                //     'issue_number'=>1,
                //     'issue_number_bn'=>1,
                //     // 'employee_id'=>1,
                //     // 'department_id'=>1,
                //     // 'requisition_num'=>1,
                //     // 'requisition_num_bn'=>1,
                //     'sales_invoice_date'=>$issueDate,
                //     'delivery_date'=>$issueDate,
                //     // 'vehicle_num'=>'',
                //     // 'vehicle_num_bn'=>'',
                //     'total_discount'=>$r->total_discount,
                //     'customer_bin_number'=>$r->customer_bin,
                //     'customer_bin_number_bn'=>$r->customer_bin,
                //     'bank_branch_id'=>$r->customer_bank_branch,
                //     'bank_account_type_id'=>$r->customer_bank_account_type,
                //     'customer_account_number'=>$r->customer_bank_account_number,
                //     'created_at'=>Date('Y-m-d H:i:s'),
                // ];
                
                // $issueMasterID = DB::table('trns03a_issue_master')->insertGetId($issueMasterData);
                     
                $orderMasterData = [
                    'customer_id'=>$r->customer_id,
                    'customer_phone'=>$r->customer_phone,
                    // 'courier_id'=>null,
                    // 'issue_master_id'=>$issueMasterID,
                    'order_date'=>$issueDate,
                    'order_type_id'=>$r->order_type_id,
                    'store_id'=>$r->store_id,
                    // 'table_id'=>$r->customer_id,
                    // 'floor_id'=>$r->customer_id,
                    // 'table_no'=>$r->customer_id,
                    
                    // 'program_session_id'=>$r->customer_id,
                    // 'program_type_id'=>$r->customer_id,
                    // 'program_name'=>$r->customer_id,
                    // 'program_name_bn'=>$r->customer_id,
                    // 'program_date'=>$r->customer_id,
                    // 'program_start_time'=>$r->customer_id,
                    // 'program_end_time'=>$r->customer_id,
                    // 'number_of_guest'=>$r->customer_id,
                    'total_amount_without_vat'=>$r->total_issue_amount_cc,
                    'total_discount_amount'=>$r->total_discount,
                    'total_vat_amount'=>$r->total_vat_amnt,
                    'total_amount_with_vat'=>floatval($r->total_issue_amount_cc)+floatval($r->total_vat_amnt),
                    'total_est_time'=>0,
                    'waiter_user_id'=>$r->waiter_id,
                    'order_status'=>$r->order_status,
                    'status'=>0,
                    'offer'=>0,
                    'payable'=>$r->total_issue_amount_cc,
                    'remarks'=>$remarks,
                    'is_active'=>1,
                    'is_print'=>1,
                    'created_by'=>$r->created_by,
                    'created_at'=>Date('Y-m-d H:i:s'),
                ];
                // return $orderMasterData;
                $orderMasterID = DB::table('trns00g_order_master')->insertGetId($orderMasterData);
                // $orderMasterID = 0;
                foreach($r->item_row as $key=>$item) {
                    // $stockMasterData=[
                    //     'receive_Issue_master_id'=>$issueMasterID,
                    //     'tran_source_type_id'=>$r->trans_src_type_id,
                    //     'tran_type_id'=>$r->tran_type_id,
                    //     'tran_sub_type_id'=>$r->tran_sub_type_id,
                    //     'prod_type_id'=>$r->prod_type_id,
                    //     'company_id'=>$r->company_id,
                    //     'branch_id'=>$r->branch_id,
                    //     'currency_id'=>$r->currency_id,
                    //     'receive_issue_date'=>$issueDate,
                    //     'fiscal_year_id'=>$r->fiscal_year,
                    //     'vat_month_id'=>$r->vat_month,
                    //     'customer_id'=>$r->customer_id,
                    //     'item_info_id'=>$item['item_information_id'],
                    //     'uom_id'=>$item['uom_id'],
                    //     'vat_payment_method_id'=>@$item['vat_payment_method_id'],
                    //     'item_cat_for_retail_id'=>@$item['item_cat_for_retail_id'],
                    //     'vat_rate_type_id'=>$item['vat_rate_type_id'],
                    //     'challan_number'=>$r->vat_challan_number,
                    //     'challan_date'=>$issueDate,
                    //     'remarks'=>$remarks,
                    //     'remarks_bn'=>$remarks,
                    //     'created_at'=>Date('Y-m-d H:i:s'),
                    // ];
                    // $stockMasterID = DB::table('trns_itemstock_master')->insertGetId($stockMasterData);
    
                    $orderChildData[] = [
                        'order_master_id'=>$orderMasterID,
                        // 'branch_id'=>$orderMasterID,
                        // 'publisher_id'=>$orderMasterID,
                        'item_info_id'=>$item['item_information_id'],
                        // 'card_id'=>$orderMasterID,
                        'uom_id'=>$item['uom_id'],
                        'uom_short_code'=>$item['uom_short_code'],
                        'relative_factor'=>$item['relative_factor'],
                        'item_rate'=>$item['issue_rate'],
                        // 'vat_payment_method_id'=>$orderMasterID,
                        // 'item_cat_for_retail_id'=>$orderMasterID,
                        'order_qty'=>$item['issue_qty'],
                        'order_qty_adjt'=>0,
                        'mrp_value'=>$item['issue_rate'],
                        'discount_percent'=>0,
                        'discount'=>$r->total_discount,
                        'item_value_tran_curr'=>$item['item_value_tran_curr'],
                        'item_value_local_curr'=>$item['item_value_tran_curr'],
                        'vat_rate_type_id'=>@$item['vat_rate_type_id'],
                        'is_fixed_rate'=>@$item['is_fixed_rate'],
                        'vat_percent'=>$item['vat_percent'],
                        'sd_percent'=>$item['sd_percent'],
                        'sd_amount'=>$item['sd_amount'],
    
                        // 'cd_percent'=>$orderMasterID,
                        // 'cd_amount'=>$orderMasterID,
                        // 'rd_percent'=>$orderMasterID,
                        // 'rd_amount'=>$orderMasterID,
                        // 'sd_percent'=>$orderMasterID,
                        // 'sd_amount'=>$orderMasterID,
                        // 'vat_percent'=>$orderMasterID,
                        'fixed_rate_uom_id'=>@$item['fixed_rate_uom_id'],
                        'fixed_rate'=>@$item['fixed_rate'],
                        'vat_amount'=>$item['vat_amount'],
                        // 'vds_percent'=>$orderMasterID,
                        'total_amount_local_curr'=>$item['total_amount_local_curr'],
                        'item_estimated_time'=>0,
                        'process_status'=>0,
                        'is_supplimentary'=>$item['is_supp'],
                        'note'=>'',
                        'created_at'=>Date('Y-m-d H:i:s'),
                        'created_by'=>$item['created_by'],
                    ];
                    // $orderChildData;
    
                    
    
                    // $issueChildData=[
                    //     'issue_master_id'=>$issueMasterID,
                    //     'item_info_id'=>$item['item_information_id'],
                    //     'uom_id'=>$item['uom_id'],
                    //     'uom_short_code'=>$item['uom_short_code'],
                    //     'relative_factor'=>$item['relative_factor'],
                    //     'issue_qty'=>$item['issue_qty'],
                    //     'issue_rate'=>$item['issue_rate'],
                    //     'item_value_tran_curr'=>$item['item_value_tran_curr'],
                    //     'item_value_local_curr'=>$item['item_value_local_curr'],
                    //     'vat_percent'=>$item['vat_percent'],
                    //     'sd_percent'=>$item['sd_percent'],
                    //     'sd_amount'=>$item['sd_amount'],
                    //     'fixed_rate_uom_id'=>@$item['fixed_rate_uom_id'],
                    //     'fixed_rate'=>@$item['fixed_rate'],
                    //     'is_fixed_rate'=>@$item['is_fixed_rate'],
                    //     'vat_amount'=>$item['vat_amount'],
                    //     'total_amount_local_curr'=>$item['total_amount_local_curr'],
                    //     'created_by'=>$item['created_by'],
                    //     'vat_payment_method_id'=>@$item['vat_payment_method_id'],
                    //     'vat_rate_type_id'=>@$item['vat_rate_type_id'],
                    //     'item_cat_for_retail_id'=>@$item['item_cat_for_retail_id'],
                    //     'created_at'=>Date('Y-m-d H:i:s'),
                    // ];
                    // return $issueChildData;
                    // $issueChildID = DB::table('trns03b_issue_child')->insertGetId($issueChildData);
    
                    // $datas = DB::table('trns_itemstock_child')
                    //     ->join('trns_itemstock_master','trns_itemstock_child.itemstock_master_id','=','trns_itemstock_master.id')
                    //     ->select('trns_itemstock_child.*','trns_itemstock_master.item_info_id')
                    //     ->where('trns_itemstock_master.item_info_id',$item['item_information_id'])
                    //     ->where('trns_itemstock_child.store_id',$r->store_id)
                    //     ->orderBy('trns_itemstock_child.created_at','DESC')
                    //     ->first();
                    // $rcvAmount = 0;
                    // if($datas){
                    //     $clsBlncQty = $datas->closing_bal_qty;
                    //     $clsBlncAmnt = $datas->closing_bal_amount;
                    //     $opBlncQty = $datas->opening_bal_qty;
                    //     $opBlncAmnt = $datas->opening_bal_amount;
                    //     $rcvQty = 0;
    
                    //     $NopBalRate = 0;
                    //     if($opBlncAmnt != 0 && $opBlncQty != 0){
                    //         $NopBalRate = $opBlncAmnt/$opBlncQty;
                    //     }
                    // }else{
                    //     $clsBlncQty = 0;
                    //     $clsBlncAmnt = 0;
                    //     $opBlncQty = 0;
                    //     $opBlncAmnt = 0;
                    //     $rcvQty = 0;
                    //     $NopBalRate = 0;
                    // }
                    // $issueQty = doubleval($item['issue_qty'])*doubleval(@$item['relative_factor'])/doubleval(@$item['sales_rel_fact']);
                    // $issueRate = doubleval($item['item_value_local_curr'])/doubleval($issueQty);
                    // $NclsBlncQty = doubleval($clsBlncQty)+doubleval($rcvQty)-doubleval($issueQty);
                    // $NclsBlncAmnt = doubleval($clsBlncAmnt)+doubleval($rcvAmount)-doubleval($item['item_value_local_curr']);
                    // if($NclsBlncQty == 0){
                    //     $NclsBlncRate = 0;
                    // }else{
                    //     $NclsBlncRate = $NclsBlncAmnt/$NclsBlncQty;
                    // }
    
                    // $stockChildData[]=[
                    //     'itemstock_master_id'=> $stockMasterID,
                    //     'receive_issue_child_id'=>$issueChildID,
                    //     'store_id'=>$r->store_id,
                    //     'opening_bal_qty'=>$clsBlncQty,
                    //     'opening_bal_rate'=>$NopBalRate,
                    //     'opening_bal_amount'=>$clsBlncAmnt,
                    //     'issue_qty'=>$issueQty,
                    //     'issue_rate'=>$issueRate,
                    //     'issue_amount'=>$item['item_value_local_curr'],
                    //     'issue_vat_percent'=>$item['vat_percent'],
                    //     'issue_vat_amnt'=>$item['vat_amount'],
                    //     'issue_sd_percent'=>$item['sd_percent'],
                    //     'issue_sd_amnt'=>$item['sd_amount'],
                    //     'closing_bal_qty'=>$NclsBlncQty,
                    //     'closing_bal_amount'=>$NclsBlncAmnt,
                    //     'closing_bal_rate'=>$NclsBlncRate,
                    //     'created_by'=>$item['created_by'],
                    //     'created_at'=>Date('Y-m-d H:i:s'),
                    // ];
                }
                $tableData = [];
                foreach($r->tableList as $table){
                    $tableData[] = [
                        'order_master_id'=>$orderMasterID,
                        'store_id'=>$table['store_id'],
                        'floor_id'=>$table['floor_id'],
                        'room_id'=>$table['room_id'],
                        'table_id'=>$table['table_id'],
                        'is_active'=>$table['is_active'],
                        'created_by'=>$table['created_by'],
                        'created_at'=>Date('Y-m-d H:i:s'),
                    ];
                }
                DB::table('trns00h1_order_child_room')->insert($tableData);
               
                    DB::table('trns00h_order_child')->insert($orderChildData);
                    // DB::table('trns50a_payment_master')->insert([
                    //     'issue_master_id'=>$issueMasterID,
                    //     'payment_date'=>$issueDate,
                    //     'prepaid_card_fs_id'=>0,
                    //     'card_id'=>0,
                    //     'paymode_id'=>1,
                    //     'paid_amount'=>$r->total_issue_amount_cc,
                    //     'pay_ref'=>'',
                    //     'created_at'=>Date('Y-m-d H:i:s'),
                    //     'created_by'=>$r->created_by,
                    // ]);
                    // DB::table('trns_itemstock_child')->insert($stockChildData);
    
                    if($isLog == false){
                        DB::table('trans_issues_log')->insert([
                            'user_id'=>$r->created_by,
                            'issue_ref'=>$r->id,
                            'guard'=>$r->log_time,
                            'issue_request_data'=>$r,
                            'created_at'=>Date('Y-m-d H:i:s')
                        ]);
                    }
                DB::commit();
                return response()->json([
                    'status'=>true,
                    'reason'=>'OK',
                    'issue_master_id'=>$orderMasterID,
                    'itemstock_master_id'=>$orderMasterID,
                    'order_master_id'=>$orderMasterID,
                ]);
    
            }catch (\Exception $e) {
                DB::rollBack();
                DB::table('trans_issues_error_log')->insert([
                    'user_id'=>$r->created_by,
                    'issue_ref'=>$r->id,
                    'issue_response'=>$e->getMessage(),
                    'created_at'=>Date('Y-m-d H:i:s')
                ]);
                return response()->json([
                    'status'=>false,
                    'reason'=>$e->getMessage(),
                    'issue_master_id'=>'',
                    'itemstock_master_id'=>''
                ]);
            }
        }elseif($r->onlineOrderId != '' && $r->is_update == '1'){
            $existingOrder =  DB::table('trns00g_order_master')->where('id',$r->onlineOrderId)->first();
            if(!$existingOrder){
                DB::table('trans_issues_error_log')->insert([
                    'user_id'=>$r->created_by,
                    'guard'=>$r->log_time,
                    'issue_ref'=>$r->id,
                    'issue_response'=>' Ordern ID not found :'.$r->onlineOrderId,
                    'created_at'=>Date('Y-m-d H:i:s')
                ]);
                return [
                    'status'=>false,
                    'reason'=>'Ordern ID not found',
                    'issue_master_id'=>'',
                    'itemstock_master_id'=>'',
                ];
            }
            DB::beginTransaction();
            try{
                $issueDate = Date('Y-m-d H:i:s',strtotime($r->issue_date));     
                $orderMasterData = [
                    'total_amount_without_vat'=>$r->total_issue_amount_cc,
                    'total_discount_amount'=>$r->total_discount,
                    'total_vat_amount'=>$r->total_vat_amnt,
                    'total_amount_with_vat'=>floatval($r->total_issue_amount_cc)+floatval($r->total_vat_amnt),
                    'order_status'=>$r->order_status,
                    'payable'=>$r->total_issue_amount_cc,
                    'remarks'=>$remarks,
                    'updated_by'=>$r->created_by,
                    'updated_at'=>Date('Y-m-d H:i:s'),
                ];
                $orderMasterID =$existingOrder->id;
                $isUpdateOrder = DB::table('trns00g_order_master')->where('id','=',$orderMasterID)->update($orderMasterData);
                foreach($r->item_row as $key=>$item) {
                    $orderChildData[] = [
                        'order_master_id'=>$orderMasterID,
                        'item_info_id'=>$item['item_information_id'],
                        // 'card_id'=>$orderMasterID,
                        'uom_id'=>$item['uom_id'],
                        'uom_short_code'=>$item['uom_short_code'],
                        'relative_factor'=>$item['relative_factor'],
                        'item_rate'=>$item['issue_rate'],
                        'order_qty'=>$item['issue_qty'],
                        'order_qty_adjt'=>0,
                        'mrp_value'=>$item['issue_rate'],
                        'discount_percent'=>0,
                        'discount'=>$r->total_discount,
                        'item_value_tran_curr'=>$item['item_value_tran_curr'],
                        'item_value_local_curr'=>$item['item_value_tran_curr'],
                        'vat_rate_type_id'=>@$item['vat_rate_type_id'],
                        'is_fixed_rate'=>@$item['is_fixed_rate'],
                        'vat_percent'=>$item['vat_percent'],
                        'sd_percent'=>$item['sd_percent'],
                        'sd_amount'=>$item['sd_amount'],
                        'fixed_rate_uom_id'=>@$item['fixed_rate_uom_id'],
                        'fixed_rate'=>@$item['fixed_rate'],
                        'vat_amount'=>$item['vat_amount'],
                        'total_amount_local_curr'=>$item['total_amount_local_curr'],
                        'item_estimated_time'=>0,
                        'process_status'=>0,
                        'is_supplimentary'=>$item['is_supp'],
                        'note'=>'',
                        'created_at'=>Date('Y-m-d H:i:s'),
                        'created_by'=>$item['created_by'],
                    ];
                }
                $tableData = [];
                foreach($r->tableList as $table){
                    $tableData[] = [
                        'order_master_id'=>$orderMasterID,
                        'store_id'=>$table['store_id'],
                        'floor_id'=>$table['floor_id'],
                        'room_id'=>$table['room_id'],
                        'table_id'=>$table['table_id'],
                        'is_active'=>$table['is_active'],
                        'created_by'=>$table['created_by'],
                    ];
                }

                
                DB::table('trns00h1_order_child_room')->where('order_master_id','=',$orderMasterID)->delete();
                DB::table('trns00h_order_child')->where('order_master_id','=',$orderMasterID)->delete();
                DB::table('trns00h1_order_child_room')->insert($tableData);
                DB::table('trns00h_order_child')->insert($orderChildData);
                if($isLog == false){
                    DB::table('trans_issues_log')->insert([
                        'user_id'=>$r->created_by,
                        'issue_ref'=>$r->id,
                        'guard'=>$r->log_time,
                        'issue_request_data'=>$r,
                        'created_at'=>Date('Y-m-d H:i:s')
                    ]);
                }
                DB::commit();
                return response()->json([
                    'status'=>true,
                    'reason'=>'Updated Successfully',
                    'issue_master_id'=>$orderMasterID,
                    'itemstock_master_id'=>$orderMasterID,
                    'order_master_id'=>$orderMasterID,
                ]);
    
            }catch (\Exception $e) {
                DB::rollBack();
                DB::table('trans_issues_error_log')->insert([
                    'user_id'=>$r->created_by,
                    'issue_ref'=>$r->id,
                    'issue_response'=>$e->getMessage(),
                    'created_at'=>Date('Y-m-d H:i:s')
                ]);
                return response()->json([
                    'status'=>false,
                    'reason'=>$e->getMessage(),
                    'issue_master_id'=>'',
                    'itemstock_master_id'=>''
                ]);
            }
        }else{
            DB::table('trans_issues_error_log')->insert([
                'user_id'=>$r->created_by,
                'guard'=>$r->log_time,
                'issue_ref'=>$r->id,
                'issue_response'=>'Order ID Exist . Order ID :'.$r->onlineOrderId,
                'created_at'=>Date('Y-m-d H:i:s')
            ]);
            return [
                'status'=>true,
                'reason'=>'Order Data Alredy Added',
                'issue_master_id'=>'',
                'itemstock_master_id'=>'',
            ];
        }

    }

    /// payment order transaction
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function StoreOrderPaymentTransaction(Request $r){
        date_default_timezone_set('Asia/Dhaka');
        
        $cartDataJson = $r->cart_data;
        $cartDataJson = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $cartDataJson),true);
        $id = $cartDataJson['id'];
        $transTime = $cartDataJson['trans_time'];
        $userId = $cartDataJson['user_id'];
        $paymentDate = Date('Y-m-d H:i:s',strtotime($cartDataJson['paymentDate']));
        $appVersion = $r->app_version;
        $issueTimeLog = strtotime($paymentDate);
        DB::table('trans_issues_init_log')->insert([
            'user_id'=>$userId,
            'issue_ref'=>$id,
            'guard'=>$issueTimeLog,
            'version'=>$appVersion,
            'issue_request_data'=>$r->cart_data,
            'created_at'=>Date('Y-m-d H:i:s')
        ]);
        // if($userId != '343'){
        //     return [
        //         'status'=>false,
        //         'reason'=>'working on server',
        //         'issue_master_id'=>'',
        //         'itemstock_master_id'=>'',
        //     ];
        // }

        $userData = DB::table('users')->where('id',$userId)->first();
        if(!$userData){
            return [
                'status'=>false,
                'message'=>'User not found',
            ];
        }

        $userStoreId = $cartDataJson['user_store_id'];
        $onlineOrderId = $cartDataJson['onlineOrderId'];
        $waiterId = $cartDataJson['waiterId'];
        $isUpdate = $cartDataJson['isUpdate'];
        $orderType = $cartDataJson['orderType'];
        $orderStatus = $cartDataJson['orderStatus'];
        $totalDiscount = $cartDataJson['totalDiscountAmount'];
        $discountRefId = $cartDataJson['discountRefId'];
        $payDueRefId = $cartDataJson['payDueRefId'];
        $discountRef = $cartDataJson['discountRef'];
        $totalVatPercent = $cartDataJson['totalVatPercent'];
        $totalVatAmount = $cartDataJson['totalVatAmount'];
        
        $totalPayableAmount = $cartDataJson['totalPayableAmount'];
        $totalPaidAmount = $cartDataJson['totalPaidAmount'];
        $totalDueAmount = $cartDataJson['totalDueAmount'];
        $paymentMethodId = $cartDataJson['paymentMethodId'];
        $payRef = $cartDataJson['payRef'];
        $paymentRemark = $cartDataJson['paymentRemark'];
        $custAddress = $cartDataJson['customerName'];
        $customerName = $cartDataJson['customerName'];
        $customerPhone = '0'.ltrim($cartDataJson['customerPhone'],'0');
        $custData = DB::table('cs_customer_contact_info')->where('phone',$customerPhone)->first();
        
        if($custData){
            $customerId = $custData->customer_id;
        }else{
            $custDetails = [
                'company_id'=>1,
                'vat_reg_id'=>1,
                'customer_name'=>$customerName,
                'customer_name_bn'=>$customerName,
                'email_address'=>'demo@email.com',
                'address'=>$custAddress,
                'address_bn'=>$custAddress,
            ];
            $custID = DB::table('cs_customer_details')->insertGetId($custDetails);
            
            if($custID){
                $custContact = [
                    'customer_id'=>$custID,
                    'phone'=>$customerPhone,
                    'country'=>1,
                    'email_address'=>'demo@email.com',
                ];
                $custConID = DB::table('cs_customer_contact_info')->insertGetId($custContact);
                $customerId = $custID;
            }else{
                $customerId = '';
                DB::table('trans_issues_error_log')->insert([
                    'user_id'=>$userId,
                    'guard'=>$issueTimeLog,
                    'issue_ref'=>$id,
                    'issue_response'=>'Customer Not Found',
                    'created_at'=>Date('Y-m-d H:i:s')
                ]);
                return [
                    'status'=>false,
                    'reason'=>'Customer Not Found',
                    'issue_master_id'=>'',
                    'itemstock_master_id'=>'',
                ];
            }
        }
        // return $customerPhone;
        $totalSellAmount = 720;
        $itemIdArr = json_decode($cartDataJson['item_id']);
        $itemNameArr = json_decode($cartDataJson['item_name']);
        $itemRateArr = json_decode($cartDataJson['item_rate']);
        $itemQtyArr = json_decode($cartDataJson['item_qty']);
        $itemAmountArr = json_decode($cartDataJson['item_amount']);
        $defVatRateIdArr = json_decode($cartDataJson['def_vat_rate_id']);
        $hsCodeIdArr = json_decode($cartDataJson['hs_code_id']);
        $tableNoArr = json_decode($cartDataJson['tableId']);
        $isSuppArr = json_decode($cartDataJson['isSupp']);
        $totalMasterPrice = array_sum($itemAmountArr);
        $i = 0;
        $printDataArr = [];

        $tableDataArr = [];
        foreach($tableNoArr as $t=>$table){
            $tableData = DB::table('var_restaurant_table')->select('room_id','id','floor_id')->where('id',$table)->first();
            if($tableData){
                $tableDataArr[$t]['store_id'] = $userStoreId;
                $tableDataArr[$t]['floor_id']=$tableData->floor_id;
                $tableDataArr[$t]['room_id']=$tableData->room_id;
                $tableDataArr[$t]['table_id']=$tableData->id;
                $tableDataArr[$t]['is_active']=1;
                $tableDataArr[$t]['created_by']=$userId;
            }
            
        }

        for($k=0;count($itemIdArr) > $k;$k++){
            if($k == 0){
                $resultArr['onlineOrderId'] = $onlineOrderId;
                $resultArr['is_update'] = $isUpdate;
                $resultArr['company_id'] = $userData->company_id;
                $resultArr['branch_id'] = $userData->branch_id;
                $resultArr['store_id'] = $userStoreId;
                $resultArr['order_type_id'] = $orderType;
                $resultArr['trans_src_type_id'] = 2;
                $resultArr['tran_type_id'] = 2;
                $resultArr['tran_sub_type_id'] = 4;
                $resultArr['prod_type_id'] = 3;
                $resultArr['waiter_id'] = $waiterId;
                $resultArr['customer_id'] = $customerId;
                $resultArr['customer_phone'] = $customerPhone;
                $resultArr['order_status'] = $orderStatus;
                $resultArr['id'] = $id;
                $resultArr['currency_id'] = 1;
                $resultArr['excg_rate'] = 1;
                $resultArr['log_time'] = $issueTimeLog;
                $resultArr['issue_date'] = Date('Y-m-d H:i:s',strtotime($transTime));
                $resultArr['monthly_proc_status'] = 0;
                $resultArr['vat_challan_date'] = Date('Y-m-d H:i:s');
                $resultArr['total_sd_amount'] = 0;
                $resultArr['fiscal_year'] = 1;
                $resultArr['vat_month'] = 1;
                $resultArr['total_vat_percent'] = $totalVatPercent;
                $resultArr['total_vat_amnt'] = $totalVatAmount;
                $resultArr['total_discount'] = $totalDiscount;
                $resultArr['discount_ref'] = $discountRef;
                $resultArr['discount_ref_id'] = $discountRefId;
                $resultArr['due_ref_id'] = $payDueRefId;
                $resultArr['total_sales_amount'] = $totalMasterPrice;
                $resultArr['total_payable_amount'] = $totalPayableAmount;
                $resultArr['total_paid_amount'] = $totalPaidAmount;
                $resultArr['total_due_amount'] = $totalDueAmount;
                $resultArr['pay_method_id'] = $paymentMethodId;
                $resultArr['pay_ref'] = $payRef;
                $resultArr['pay_remark'] = $paymentRemark;
                $resultArr['pay_date'] = $paymentDate;
                $resultArr['reg_status'] = 1;
                $resultArr['created_by'] = $userId;
                $resultArr['req_no'] = $id;
                $resultArr['created_at'] = Date('Y-m-d H:i:s');
                $resultArr['tableList'] = $tableDataArr;
                $resultArr['item_row'] = [];
            }
            $defVatRateId = $defVatRateIdArr[$k];
            $hsCodeId = $hsCodeIdArr[$k];
            $index = $i;
            $vatData = DB::table('var_vat_structure_rates AS vsr')
                        ->where('hs_code_id',$hsCodeId)
                        ->where('vat_rate_type_id',$defVatRateId)
                        ->select('vat','sd')
                        ->orderby('id','desc')
                        ->first();

            if($vatData){
                if($vatData->sd == ''){
                    $sd =0;
                }
                else{
                    $sd = $vatData->sd;
                }
                if ($vatData->vat == ''){
                    $vat =0;
                }
                else{
                    $vat = $vatData->vat;
                }
            }else{
                $sd =0;
                $vat =0;
            }
            $itemId = $itemIdArr[$k];
            $totalAmount = $itemAmountArr[$k];
            $itemRate = $itemRateArr[$k];
            $itemQty = $itemQtyArr[$k];
            $itemName = $itemNameArr[$k];
            $isSupp = $isSuppArr[$k];
            $sdAmount = ($totalAmount*$sd)/100;
            $totalWithSd = $totalAmount+$sdAmount;
            $vatAmount = ($totalWithSd*$vat)/100;
            $grandTotal = $totalWithSd+$vatAmount;
            $resultArr['item_row'][$i]['item_information_id'] = $itemId;
            $resultArr['item_row'][$i]['uom_id'] = 1;
            $resultArr['item_row'][$i]['uom_short_code'] = 'Pcs';
            $resultArr['item_row'][$i]['relative_factor'] = 1;
            $resultArr['item_row'][$i]['sales_rel_fact'] = 1;
            $resultArr['item_row'][$i]['issue_qty'] = $itemQty;
            $resultArr['item_row'][$i]['issue_rate'] = $itemRate;
            $resultArr['item_row'][$i]['item_value_tran_curr'] = $totalAmount;
            $resultArr['item_row'][$i]['item_value_local_curr'] = $totalAmount;
            $resultArr['item_row'][$i]['is_fixed_rate'] = 0;
            $resultArr['item_row'][$i]['is_supp'] = $isSupp;
            $resultArr['item_row'][$i]['sd_percent'] = $sd;
            $resultArr['item_row'][$i]['sd_amount'] = $sdAmount;
            $resultArr['item_row'][$i]['vat_percent'] = $vat;
            $resultArr['item_row'][$i]['vat_amount'] = $vatAmount;
            $resultArr['item_row'][$i]['vat_rate_type_id'] = 5;
            $resultArr['item_row'][$i]['total_amount_local_curr'] = $grandTotal;
            $resultArr['item_row'][$i]['created_by'] = $userId;
            $i++;
        }
        // return $resultArr;
        $isStore = Http::post("http://159.223.67.50/chiklee_test_server/chiklee_api/public/api/ticket_api/item-isssue-create-one-take-order-payment",$resultArr)->json();
        return [
            'status'=>$isStore['status'],
            'reason'=>$isStore['reason'],
            'issue_master_id'=>$isStore['issue_master_id'],
            'itemstock_master_id'=>$isStore['itemstock_master_id'],
            // 'print_data'=>$printDataArr,
        ];
    }

    public function ItemIssueCreateOneTakeOrderPayment(Request $r){
        // return $r;
        date_default_timezone_set('Asia/Dhaka');
        $isLog = false;

        $total_sd_amount=$r->total_sd_amount;
        if ($total_sd_amount == '')
        {
            $total_sd_amount=0;
        }
        $remarks=$r->remark;
        if ($remarks == '')
        {
            $remarks = $r->id;
        }
        $reg_status = 1;
        if($r->reg_status == ''){
            $reg_status = 0;
        }
        $monthly_proc_status = 1;
        if($r->monthly_proc_status == ''){
            $monthly_proc_status = 0;
        }
        if($r->onlineOrderId == ''){
            $isDuplicate = DB::table('trans_issues_log')->where('user_id',$r->created_by)->where('issue_ref',$r->id)->where('guard',$r->log_time)->first();
            if($isDuplicate){
                DB::table('trans_issues_error_log')->insert([
                    'user_id'=>$r->created_by,
                    'guard'=>$r->log_time,
                    'issue_ref'=>$r->id,
                    'issue_response'=>'('.$isDuplicate->id.') Data Already Exist',
                    'created_at'=>Date('Y-m-d H:i:s')
                ]);
                return [
                    'status'=>true,
                    'reason'=>'Data Already Exist',
                    'issue_master_id'=>'',
                    'itemstock_master_id'=>'',
                ];
                $isLog = true;
            }

            DB::beginTransaction();
            try{
                $issueDate = Date('Y-m-d H:i:s',strtotime($r->issue_date));
    
                $issueMasterData=[
                    'tran_source_type_id'=>$r->trans_src_type_id,
                    'tran_type_id'=>$r->tran_type_id,
                    'tran_sub_type_id'=>$r->tran_sub_type_id,
                    'prod_type_id'=>$r->prod_type_id,
                    'company_id'=>$r->company_id,
                    'branch_id'=>$r->branch_id,
                    'store_id'=>$r->store_id,
                    'currency_id'=>$r->currency_id,
                    'excg_rate'=>$r->excg_rate,
                    'customer_id'=>$r->customer_id,
                    'reg_status'=>$reg_status,
                    'issue_date'=>$issueDate,
                    'fiscal_year_id'=>$r->fiscal_year,
                    'vat_month_id'=>$r->vat_month,
                    'challan_type'=>$r->challan_type,
                    'challan_number'=>$r->vat_challan_number,
                    'challan_number_bn'=>$r->vat_challan_number,
                    'challan_date'=>$issueDate,
                    'total_sd_amnt'=>$total_sd_amount,
                    'total_issue_amount_before_discount'=>$r->total_sales_amount,
                    'total_issue_amt_local_curr_before_discount'=>$r->total_sales_amount,
                    'total_issue_amount'=>$r->total_payable_amount,
                    'total_issue_amt_local_curr'=>$r->total_payable_amount,
                    'total_discount'=>$r->total_discount,
                    'employee_id'=>$r->discount_ref_id,
                    'total_vat_amnt'=>$r->total_vat_amnt,
                    'remarks'=>$remarks,
                    'remarks_bn'=>$remarks,
                    'monthly_proc_status'=>$monthly_proc_status,
                    'created_by'=>$r->created_by,
                    'updated_by'=>$r->updated_by,
                    'issue_number'=>1,
                    'issue_number_bn'=>1,
                    // 'employee_id'=>1,
                    // 'department_id'=>1,
                    // 'requisition_num'=>1,
                    // 'requisition_num_bn'=>1,
                    'sales_invoice_date'=>$issueDate,
                    'delivery_date'=>$issueDate,
                    // 'vehicle_num'=>'',
                    // 'vehicle_num_bn'=>'',
                    'customer_bin_number'=>$r->customer_bin,
                    'customer_bin_number_bn'=>$r->customer_bin,
                    'bank_branch_id'=>$r->customer_bank_branch,
                    'bank_account_type_id'=>$r->customer_bank_account_type,
                    'customer_account_number'=>$r->customer_bank_account_number,
                    'created_at'=>Date('Y-m-d H:i:s'),
                ];
                
                $issueMasterID = DB::table('trns03a_issue_master')->insertGetId($issueMasterData);
                     
                $orderMasterData = [
                    'customer_id'=>$r->customer_id,
                    'customer_phone'=>$r->customer_phone,
                    'issue_master_id'=>$issueMasterID,
                    'order_date'=>$issueDate,
                    'order_type_id'=>$r->order_type_id,
                    'store_id'=>$r->store_id,
                    'total_amount_without_vat'=>$r->total_sales_amount,
                    'total_discount_amount'=>$r->total_discount,
                    'total_vat_amount'=>$r->total_vat_amnt,
                    'total_amount_with_vat'=>floatval($r->total_sales_amount)+floatval($r->total_vat_amnt),
                    'total_est_time'=>0,
                    'waiter_user_id'=>$r->waiter_id,
                    'order_status'=>$r->order_status,
                    'status'=>1,
                    'offer'=>0,
                    'payable'=>$r->total_payable_amount,
                    'remarks'=>$remarks,
                    'is_active'=>1,
                    'is_print'=>1,
                    'created_by'=>$r->created_by,
                    'created_at'=>Date('Y-m-d H:i:s'),
                ];
                // return $orderMasterData;
                $orderMasterID = DB::table('trns00g_order_master')->insertGetId($orderMasterData);
                // $orderMasterID = 0;
                foreach($r->item_row as $key=>$item) {
                    $stockMasterData=[
                        'receive_Issue_master_id'=>$issueMasterID,
                        'tran_source_type_id'=>$r->trans_src_type_id,
                        'tran_type_id'=>$r->tran_type_id,
                        'tran_sub_type_id'=>$r->tran_sub_type_id,
                        'prod_type_id'=>$r->prod_type_id,
                        'company_id'=>$r->company_id,
                        'branch_id'=>$r->branch_id,
                        'currency_id'=>$r->currency_id,
                        'receive_issue_date'=>$issueDate,
                        'fiscal_year_id'=>$r->fiscal_year,
                        'vat_month_id'=>$r->vat_month,
                        'customer_id'=>$r->customer_id,
                        'item_info_id'=>$item['item_information_id'],
                        'uom_id'=>$item['uom_id'],
                        'vat_payment_method_id'=>@$item['vat_payment_method_id'],
                        'item_cat_for_retail_id'=>@$item['item_cat_for_retail_id'],
                        'vat_rate_type_id'=>$item['vat_rate_type_id'],
                        'challan_number'=>$r->vat_challan_number,
                        'challan_date'=>$issueDate,
                        'remarks'=>$remarks,
                        'remarks_bn'=>$remarks,
                        'created_at'=>Date('Y-m-d H:i:s'),
                    ];
                    $stockMasterID = DB::table('trns_itemstock_master')->insertGetId($stockMasterData);
    
                    $orderChildData[] = [
                        'order_master_id'=>$orderMasterID,
                        // 'branch_id'=>$orderMasterID,
                        // 'publisher_id'=>$orderMasterID,
                        'item_info_id'=>$item['item_information_id'],
                        // 'card_id'=>$orderMasterID,
                        'uom_id'=>$item['uom_id'],
                        'uom_short_code'=>$item['uom_short_code'],
                        'relative_factor'=>$item['relative_factor'],
                        'item_rate'=>$item['issue_rate'],
                        // 'vat_payment_method_id'=>$orderMasterID,
                        // 'item_cat_for_retail_id'=>$orderMasterID,
                        'order_qty'=>$item['issue_qty'],
                        'order_qty_adjt'=>0,
                        'mrp_value'=>$item['issue_rate'],
                        'discount_percent'=>0,
                        'discount'=>$r->total_discount,
                        'item_value_tran_curr'=>$item['item_value_tran_curr'],
                        'item_value_local_curr'=>$item['item_value_tran_curr'],
                        'vat_rate_type_id'=>@$item['vat_rate_type_id'],
                        'is_fixed_rate'=>@$item['is_fixed_rate'],
                        'vat_percent'=>$item['vat_percent'],
                        'sd_percent'=>$item['sd_percent'],
                        'sd_amount'=>$item['sd_amount'],
    
                        // 'cd_percent'=>$orderMasterID,
                        // 'cd_amount'=>$orderMasterID,
                        // 'rd_percent'=>$orderMasterID,
                        // 'rd_amount'=>$orderMasterID,
                        // 'sd_percent'=>$orderMasterID,
                        // 'sd_amount'=>$orderMasterID,
                        // 'vat_percent'=>$orderMasterID,
                        'fixed_rate_uom_id'=>@$item['fixed_rate_uom_id'],
                        'fixed_rate'=>@$item['fixed_rate'],
                        'vat_amount'=>$item['vat_amount'],
                        // 'vds_percent'=>$orderMasterID,
                        'total_amount_local_curr'=>$item['total_amount_local_curr'],
                        'item_estimated_time'=>0,
                        'process_status'=>0,
                        'is_supplimentary'=>$item['is_supp'],
                        'note'=>'',
                        'created_at'=>Date('Y-m-d H:i:s'),
                        'created_by'=>$item['created_by'],
                    ];
                    // $orderChildData;
    
                    
    
                    $issueChildData=[
                        'issue_master_id'=>$issueMasterID,
                        'item_info_id'=>$item['item_information_id'],
                        'uom_id'=>$item['uom_id'],
                        'uom_short_code'=>$item['uom_short_code'],
                        'relative_factor'=>$item['relative_factor'],
                        'issue_qty'=>$item['issue_qty'],
                        'issue_rate'=>$item['issue_rate'],
                        'item_value_tran_curr'=>$item['item_value_tran_curr'],
                        'item_value_local_curr'=>$item['item_value_local_curr'],
                        'vat_percent'=>$item['vat_percent'],
                        'sd_percent'=>$item['sd_percent'],
                        'sd_amount'=>$item['sd_amount'],
                        'fixed_rate_uom_id'=>@$item['fixed_rate_uom_id'],
                        'fixed_rate'=>@$item['fixed_rate'],
                        'is_fixed_rate'=>@$item['is_fixed_rate'],
                        'vat_amount'=>$item['vat_amount'],
                        'total_amount_local_curr'=>$item['total_amount_local_curr'],
                        'created_by'=>$item['created_by'],
                        'vat_payment_method_id'=>@$item['vat_payment_method_id'],
                        'vat_rate_type_id'=>@$item['vat_rate_type_id'],
                        'item_cat_for_retail_id'=>@$item['item_cat_for_retail_id'],
                        'created_at'=>Date('Y-m-d H:i:s'),
                    ];
                    // return $issueChildData;
                    $issueChildID = DB::table('trns03b_issue_child')->insertGetId($issueChildData);
    
                    $datas = DB::table('trns_itemstock_child')
                        ->join('trns_itemstock_master','trns_itemstock_child.itemstock_master_id','=','trns_itemstock_master.id')
                        ->select('trns_itemstock_child.*','trns_itemstock_master.item_info_id')
                        ->where('trns_itemstock_master.item_info_id',$item['item_information_id'])
                        ->where('trns_itemstock_child.store_id',$r->store_id)
                        ->orderBy('trns_itemstock_child.created_at','DESC')
                        ->first();
                    $rcvAmount = 0;
                    if($datas){
                        $clsBlncQty = $datas->closing_bal_qty;
                        $clsBlncAmnt = $datas->closing_bal_amount;
                        $opBlncQty = $datas->opening_bal_qty;
                        $opBlncAmnt = $datas->opening_bal_amount;
                        $rcvQty = 0;
    
                        $NopBalRate = 0;
                        if($opBlncAmnt != 0 && $opBlncQty != 0){
                            $NopBalRate = $opBlncAmnt/$opBlncQty;
                        }
                    }else{
                        $clsBlncQty = 0;
                        $clsBlncAmnt = 0;
                        $opBlncQty = 0;
                        $opBlncAmnt = 0;
                        $rcvQty = 0;
                        $NopBalRate = 0;
                    }
                    $issueQty = doubleval($item['issue_qty'])*doubleval(@$item['relative_factor'])/doubleval(@$item['sales_rel_fact']);
                    $issueRate = doubleval($item['item_value_local_curr'])/doubleval($issueQty);
                    $NclsBlncQty = doubleval($clsBlncQty)+doubleval($rcvQty)-doubleval($issueQty);
                    $NclsBlncAmnt = doubleval($clsBlncAmnt)+doubleval($rcvAmount)-doubleval($item['item_value_local_curr']);
                    if($NclsBlncQty == 0){
                        $NclsBlncRate = 0;
                    }else{
                        $NclsBlncRate = $NclsBlncAmnt/$NclsBlncQty;
                    }
    
                    $stockChildData[]=[
                        'itemstock_master_id'=> $stockMasterID,
                        'receive_issue_child_id'=>$issueChildID,
                        'store_id'=>$r->store_id,
                        'opening_bal_qty'=>$clsBlncQty,
                        'opening_bal_rate'=>$NopBalRate,
                        'opening_bal_amount'=>$clsBlncAmnt,
                        'issue_qty'=>$issueQty,
                        'issue_rate'=>$issueRate,
                        'issue_amount'=>$item['item_value_local_curr'],
                        'issue_vat_percent'=>$item['vat_percent'],
                        'issue_vat_amnt'=>$item['vat_amount'],
                        'issue_sd_percent'=>$item['sd_percent'],
                        'issue_sd_amnt'=>$item['sd_amount'],
                        'closing_bal_qty'=>$NclsBlncQty,
                        'closing_bal_amount'=>$NclsBlncAmnt,
                        'closing_bal_rate'=>$NclsBlncRate,
                        'created_by'=>$item['created_by'],
                        'created_at'=>Date('Y-m-d H:i:s'),
                    ];
                }
                $tableData = [];
                foreach($r->tableList as $table){
                    $tableData[] = [
                        'order_master_id'=>$orderMasterID,
                        'store_id'=>$table['store_id'],
                        'floor_id'=>$table['floor_id'],
                        'room_id'=>$table['room_id'],
                        'table_id'=>$table['table_id'],
                        'is_active'=>$table['is_active'],
                        'created_by'=>$table['created_by'],
                        'created_at'=>Date('Y-m-d H:i:s'),
                    ];
                }
                DB::table('trns00h1_order_child_room')->insert($tableData);
               
                    DB::table('trns00h_order_child')->insert($orderChildData);
                    DB::table('trns50a_payment_master')->insert([
                        'order_id'=>$orderMasterID,
                        'issue_master_id'=>$issueMasterID,
                        'payment_date'=>$r->pay_date,
                        'due_ref_id'=>$r->due_ref_id,
                        'prepaid_card_fs_id'=>0,
                        'card_id'=>0,
                        'paymode_id'=>$r->pay_moethod_id,
                        'paid_amount'=>$r->total_paid_amount,
                        'pay_ref'=>$r->pay_ref,
                        'remarks'=>$r->pay_remark,
                        'created_at'=>Date('Y-m-d H:i:s'),
                        'created_by'=>$r->created_by,
                    ]);
                    DB::table('trns_itemstock_child')->insert($stockChildData);
    
                    if($isLog == false){
                        DB::table('trans_issues_log')->insert([
                            'user_id'=>$r->created_by,
                            'issue_ref'=>$r->id,
                            'guard'=>$r->log_time,
                            'issue_request_data'=>$r,
                            'created_at'=>Date('Y-m-d H:i:s')
                        ]);
                    }
                DB::commit();
                return response()->json([
                    'status'=>true,
                    'reason'=>'OK',
                    'issue_master_id'=>$orderMasterID,
                    'itemstock_master_id'=>$orderMasterID,
                    'order_master_id'=>$orderMasterID,
                ]);
    
            }catch (\Exception $e) {
                DB::rollBack();
                DB::table('trans_issues_error_log')->insert([
                    'user_id'=>$r->created_by,
                    'issue_ref'=>$r->id,
                    'issue_response'=>$e->getMessage(),
                    'created_at'=>Date('Y-m-d H:i:s')
                ]);
                return response()->json([
                    'status'=>false,
                    'reason'=>$e->getMessage(),
                    'issue_master_id'=>'',
                    'itemstock_master_id'=>''
                ]);
            }
        }elseif($r->onlineOrderId != ''){
            $existingOrder =  DB::table('trns00g_order_master')->where('id',$r->onlineOrderId)->first();
            if(!$existingOrder){
                DB::table('trans_issues_error_log')->insert([
                    'user_id'=>$r->created_by,
                    'guard'=>$r->log_time,
                    'issue_ref'=>$r->id,
                    'issue_response'=>' Ordern ID not found :'.$r->onlineOrderId,
                    'created_at'=>Date('Y-m-d H:i:s')
                ]);
                return [
                    'status'=>false,
                    'reason'=>'Ordern ID not found',
                    'issue_master_id'=>'',
                    'itemstock_master_id'=>'',
                ];
            }elseif($existingOrder->is_active == 0){
                DB::table('trans_issues_error_log')->insert([
                    'user_id'=>$r->created_by,
                    'guard'=>$r->log_time,
                    'issue_ref'=>$r->id,
                    'issue_response'=>'Ordern Already Updated:'.$r->onlineOrderId,
                    'created_at'=>Date('Y-m-d H:i:s')
                ]);
                return [
                    'status'=>true,
                    'reason'=>'Ordern Already Updated',
                    'issue_master_id'=>'',
                    'itemstock_master_id'=>'',
                ];
            }
            $orderMasterID = $existingOrder->id;
            DB::beginTransaction();
            try{
                $issueDate = Date('Y-m-d H:i:s',strtotime($r->issue_date));     
            
                $issueMasterData=[
                    'tran_source_type_id'=>$r->trans_src_type_id,
                    'tran_type_id'=>$r->tran_type_id,
                    'tran_sub_type_id'=>$r->tran_sub_type_id,
                    'prod_type_id'=>$r->prod_type_id,
                    'company_id'=>$r->company_id,
                    'branch_id'=>$r->branch_id,
                    'store_id'=>$r->store_id,
                    'currency_id'=>$r->currency_id,
                    'excg_rate'=>$r->excg_rate,
                    'customer_id'=>$r->customer_id,
                    'reg_status'=>$reg_status,
                    'issue_date'=>$issueDate,
                    'fiscal_year_id'=>$r->fiscal_year,
                    'vat_month_id'=>$r->vat_month,
                    'challan_type'=>$r->challan_type,
                    'challan_number'=>$r->vat_challan_number,
                    'challan_number_bn'=>$r->vat_challan_number,
                    'challan_date'=>$issueDate,
                    'total_sd_amnt'=>$total_sd_amount,
                    'total_issue_amount_before_discount'=>$r->total_sales_amount,
                    'total_issue_amt_local_curr_before_discount'=>$r->total_sales_amount,
                    'total_issue_amount'=>$r->total_payable_amount,
                    'total_issue_amt_local_curr'=>$r->total_payable_amount,
                    'total_discount'=>$r->total_discount,
                    'employee_id'=>$r->discount_ref_id,
                    'total_vat_amnt'=>$r->total_vat_amnt,
                    'remarks'=>$remarks,
                    'remarks_bn'=>$remarks,
                    'monthly_proc_status'=>$monthly_proc_status,
                    'created_by'=>$r->created_by,
                    'updated_by'=>$r->updated_by,
                    'issue_number'=>1,
                    'issue_number_bn'=>1,
                    // 'employee_id'=>1,
                    // 'department_id'=>1,
                    // 'requisition_num'=>1,
                    // 'requisition_num_bn'=>1,
                    'sales_invoice_date'=>$issueDate,
                    'delivery_date'=>$issueDate,
                    // 'vehicle_num'=>'',
                    // 'vehicle_num_bn'=>'',
                    'customer_bin_number'=>$r->customer_bin,
                    'customer_bin_number_bn'=>$r->customer_bin,
                    'bank_branch_id'=>$r->customer_bank_branch,
                    'bank_account_type_id'=>$r->customer_bank_account_type,
                    'customer_account_number'=>$r->customer_bank_account_number,
                    'created_at'=>Date('Y-m-d H:i:s'),
                ];
                
                $issueMasterID = DB::table('trns03a_issue_master')->insertGetId($issueMasterData);

                $orderMasterData = [
                    'issue_master_id'=>$issueMasterID,
                    'total_amount_without_vat'=>$r->total_sales_amount,
                    'total_discount_amount'=>$r->total_discount,
                    'total_vat_amount'=>$r->total_vat_amnt,
                    'total_amount_with_vat'=>floatval($r->total_sales_amount)+floatval($r->total_vat_amnt),
                    'order_status'=>$r->order_status,
                    'is_active'=>0,
                    'payable'=>$r->total_payable_amount,
                    'remarks'=>$remarks,
                    'updated_by'=>$r->created_by,
                    'updated_at'=>Date('Y-m-d H:i:s'),
                    'updated_by'=>$r->created_by,
                ];
                
                $isUpdateOrder = DB::table('trns00g_order_master')->where('id','=',$orderMasterID)->update($orderMasterData);

                foreach($r->item_row as $key=>$item) {
                    $stockMasterData=[
                        'receive_Issue_master_id'=>$issueMasterID,
                        'tran_source_type_id'=>$r->trans_src_type_id,
                        'tran_type_id'=>$r->tran_type_id,
                        'tran_sub_type_id'=>$r->tran_sub_type_id,
                        'prod_type_id'=>$r->prod_type_id,
                        'company_id'=>$r->company_id,
                        'branch_id'=>$r->branch_id,
                        'currency_id'=>$r->currency_id,
                        'receive_issue_date'=>$issueDate,
                        'fiscal_year_id'=>$r->fiscal_year,
                        'vat_month_id'=>$r->vat_month,
                        'customer_id'=>$r->customer_id,
                        'item_info_id'=>$item['item_information_id'],
                        'uom_id'=>$item['uom_id'],
                        'vat_payment_method_id'=>@$item['vat_payment_method_id'],
                        'item_cat_for_retail_id'=>@$item['item_cat_for_retail_id'],
                        'vat_rate_type_id'=>$item['vat_rate_type_id'],
                        'challan_number'=>$r->vat_challan_number,
                        'challan_date'=>$issueDate,
                        'remarks'=>$remarks,
                        'remarks_bn'=>$remarks,
                        'created_at'=>Date('Y-m-d H:i:s'),
                    ];

                    $stockMasterID = DB::table('trns_itemstock_master')->insertGetId($stockMasterData);

                    $orderChildData[] = [
                        'order_master_id'=>$orderMasterID,
                        'item_info_id'=>$item['item_information_id'],
                        'uom_id'=>$item['uom_id'],
                        'uom_short_code'=>$item['uom_short_code'],
                        'relative_factor'=>$item['relative_factor'],
                        'item_rate'=>$item['issue_rate'],
                        'order_qty'=>$item['issue_qty'],
                        'order_qty_adjt'=>0,
                        'mrp_value'=>$item['issue_rate'],
                        'discount_percent'=>0,
                        'discount'=>$r->total_discount,
                        'item_value_tran_curr'=>$item['item_value_tran_curr'],
                        'item_value_local_curr'=>$item['item_value_tran_curr'],
                        'vat_rate_type_id'=>@$item['vat_rate_type_id'],
                        'is_fixed_rate'=>@$item['is_fixed_rate'],
                        'vat_percent'=>$item['vat_percent'],
                        'sd_percent'=>$item['sd_percent'],
                        'sd_amount'=>$item['sd_amount'],
                        'fixed_rate_uom_id'=>@$item['fixed_rate_uom_id'],
                        'fixed_rate'=>@$item['fixed_rate'],
                        'vat_amount'=>$item['vat_amount'],
                        'total_amount_local_curr'=>$item['total_amount_local_curr'],
                        'item_estimated_time'=>0,
                        'process_status'=>0,
                        'is_supplimentary'=>$item['is_supp'],
                        'note'=>'',
                        'created_at'=>Date('Y-m-d H:i:s'),
                        'created_by'=>$item['created_by'],
                    ];
                    $issueChildData=[
                        'issue_master_id'=>$issueMasterID,
                        'item_info_id'=>$item['item_information_id'],
                        'uom_id'=>$item['uom_id'],
                        'uom_short_code'=>$item['uom_short_code'],
                        'relative_factor'=>$item['relative_factor'],
                        'issue_qty'=>$item['issue_qty'],
                        'issue_rate'=>$item['issue_rate'],
                        'item_value_tran_curr'=>$item['item_value_tran_curr'],
                        'item_value_local_curr'=>$item['item_value_local_curr'],
                        'vat_percent'=>$item['vat_percent'],
                        'sd_percent'=>$item['sd_percent'],
                        'sd_amount'=>$item['sd_amount'],
                        'fixed_rate_uom_id'=>@$item['fixed_rate_uom_id'],
                        'fixed_rate'=>@$item['fixed_rate'],
                        'is_fixed_rate'=>@$item['is_fixed_rate'],
                        'vat_amount'=>$item['vat_amount'],
                        'total_amount_local_curr'=>$item['total_amount_local_curr'],
                        'created_by'=>$item['created_by'],
                        'vat_payment_method_id'=>@$item['vat_payment_method_id'],
                        'vat_rate_type_id'=>@$item['vat_rate_type_id'],
                        'item_cat_for_retail_id'=>@$item['item_cat_for_retail_id'],
                        'created_at'=>Date('Y-m-d H:i:s'),
                    ];
                    $issueChildID = DB::table('trns03b_issue_child')->insertGetId($issueChildData);

                    $datas = DB::table('trns_itemstock_child')
                        ->join('trns_itemstock_master','trns_itemstock_child.itemstock_master_id','=','trns_itemstock_master.id')
                        ->select('trns_itemstock_child.*','trns_itemstock_master.item_info_id')
                        ->where('trns_itemstock_master.item_info_id',$item['item_information_id'])
                        ->where('trns_itemstock_child.store_id',$r->store_id)
                        ->orderBy('trns_itemstock_child.created_at','DESC')
                        ->first();
                    $rcvAmount = 0;
                    if($datas){
                        $clsBlncQty = $datas->closing_bal_qty;
                        $clsBlncAmnt = $datas->closing_bal_amount;
                        $opBlncQty = $datas->opening_bal_qty;
                        $opBlncAmnt = $datas->opening_bal_amount;
                        $rcvQty = 0;
    
                        $NopBalRate = 0;
                        if($opBlncAmnt != 0 && $opBlncQty != 0){
                            $NopBalRate = $opBlncAmnt/$opBlncQty;
                        }
                    }else{
                        $clsBlncQty = 0;
                        $clsBlncAmnt = 0;
                        $opBlncQty = 0;
                        $opBlncAmnt = 0;
                        $rcvQty = 0;
                        $NopBalRate = 0;
                    }
                    $issueQty = doubleval($item['issue_qty'])*doubleval(@$item['relative_factor'])/doubleval(@$item['sales_rel_fact']);
                    $issueRate = doubleval($item['item_value_local_curr'])/doubleval($issueQty);
                    $NclsBlncQty = doubleval($clsBlncQty)+doubleval($rcvQty)-doubleval($issueQty);
                    $NclsBlncAmnt = doubleval($clsBlncAmnt)+doubleval($rcvAmount)-doubleval($item['item_value_local_curr']);
                    if($NclsBlncQty == 0){
                        $NclsBlncRate = 0;
                    }else{
                        $NclsBlncRate = $NclsBlncAmnt/$NclsBlncQty;
                    }
    
                    $stockChildData[]=[
                        'itemstock_master_id'=> $stockMasterID,
                        'receive_issue_child_id'=>$issueChildID,
                        'store_id'=>$r->store_id,
                        'opening_bal_qty'=>$clsBlncQty,
                        'opening_bal_rate'=>$NopBalRate,
                        'opening_bal_amount'=>$clsBlncAmnt,
                        'issue_qty'=>$issueQty,
                        'issue_rate'=>$issueRate,
                        'issue_amount'=>$item['item_value_local_curr'],
                        'issue_vat_percent'=>$item['vat_percent'],
                        'issue_vat_amnt'=>$item['vat_amount'],
                        'issue_sd_percent'=>$item['sd_percent'],
                        'issue_sd_amnt'=>$item['sd_amount'],
                        'closing_bal_qty'=>$NclsBlncQty,
                        'closing_bal_amount'=>$NclsBlncAmnt,
                        'closing_bal_rate'=>$NclsBlncRate,
                        'created_by'=>$item['created_by'],
                        'created_at'=>Date('Y-m-d H:i:s'),
                    ];
                }
                $tableData = [];
                foreach($r->tableList as $table){
                    $tableData[] = [
                        'order_master_id'=>$orderMasterID,
                        'store_id'=>$table['store_id'],
                        'floor_id'=>$table['floor_id'],
                        'room_id'=>$table['room_id'],
                        'table_id'=>$table['table_id'],
                        'is_active'=>$table['is_active'],
                        'created_by'=>$table['created_by'],
                    ];
                }

                DB::table('trns50a_payment_master')->insert([
                    'order_id'=>$orderMasterID,
                    'issue_master_id'=>$issueMasterID,
                    'payment_date'=>$r->pay_date,
                    'prepaid_card_fs_id'=>0,
                    'card_id'=>0,
                    'paymode_id'=>$r->pay_moethod_id,
                    'paid_amount'=>$r->total_paid_amount,
                    'pay_ref'=>$r->pay_ref,
                    'due_ref_id'=>$r->due_ref_id,
                    'remarks'=>$r->pay_remark,
                    'created_at'=>Date('Y-m-d H:i:s'),
                    'created_by'=>$r->created_by,
                ]);
                
                DB::table('trns00h1_order_child_room')->where('order_master_id','=',$orderMasterID)->delete();
                DB::table('trns00h_order_child')->where('order_master_id','=',$orderMasterID)->delete();
                DB::table('trns00h1_order_child_room')->insert($tableData);
                DB::table('trns00h_order_child')->insert($orderChildData);
                if($isLog == false){
                    DB::table('trans_issues_log')->insert([
                        'user_id'=>$r->created_by,
                        'issue_ref'=>$r->id,
                        'guard'=>$r->log_time,
                        'issue_request_data'=>$r,
                        'created_at'=>Date('Y-m-d H:i:s')
                    ]);
                }
                DB::commit();
                return response()->json([
                    'status'=>true,
                    'reason'=>'Updated Successfully',
                    'issue_master_id'=>$issueMasterID,
                    'itemstock_master_id'=>$stockMasterID,
                    'order_master_id'=>$orderMasterID,
                ]);
    
            }catch (\Exception $e) {
                DB::rollBack();
                DB::table('trans_issues_error_log')->insert([
                    'user_id'=>$r->created_by,
                    'issue_ref'=>$r->id,
                    'issue_response'=>$e->getMessage(),
                    'created_at'=>Date('Y-m-d H:i:s')
                ]);
                return response()->json([
                    'status'=>false,
                    'reason'=>$e->getMessage(),
                    'issue_master_id'=>'',
                    'itemstock_master_id'=>''
                ]);
            }
        }else{
            DB::table('trans_issues_error_log')->insert([
                'user_id'=>$r->created_by,
                'guard'=>$r->log_time,
                'issue_ref'=>$r->id,
                'issue_response'=>'Order ID Exist . Order ID :'.$r->onlineOrderId,
                'created_at'=>Date('Y-m-d H:i:s')
            ]);
            return [
                'status'=>true,
                'reason'=>'Order Data Alredy Added',
                'issue_master_id'=>'',
                'itemstock_master_id'=>'',
            ];
        }

    }

    public function GetRunningOrderList(Request $r){
        $userId = $r->user_id;
        $data = DB::table('trns00g_order_master as master',)
            ->join('trns00h_order_child as child', 'master.id','=','child.order_master_id')
            ->leftJoin('var_item_info as item', 'item.id','=','child.item_info_id')
            ->where('master.created_by','=',$userId)
            ->where('master.order_status','=',0)
            ->select('master.id as orderMasterId','master.order_date as orderDate','master.order_type_id as orderTypeId','master.order_status as orderStatus','child.id as orderChildId','item.id as itemId','item.display_itm_name as itemName','item.display_itm_name_bn as itemNameBn','child.order_qty as orderQty','child.process_status as orderProccessStatus')
            ->get();
        $dataArr = collect($data)->groupBy('orderMasterId');

        $result = [];
        $i = 0;
        foreach($dataArr as $masterId=>$item){
            $tableData = DB::table('trns00h1_order_child_room as room')
                ->leftJoin('var_restaurant_table as table','room.table_id','=','table.id')
                ->select('room.id as orderRoomId','table.room_id as roomId','table.floor_id as floorId','table.id as tableId','table.table_name as tableName','table.table_no as tableNo')->where('order_master_id','=',$masterId)->get();
            $tableNoShowData = '';
            foreach($tableData as $row){
                $tableNoShowData .= $row->tableNo.',';
            }
            $result[$i]['showTableNo'] = $tableNoShowData;
            $result[$i]['orderMasterId'] = $item[0]->orderMasterId;
            $result[$i]['orderDate'] = $item[0]->orderDate;
            $result[$i]['orderTypeId'] = $item[0]->orderTypeId;
            $result[$i]['orderStatus'] = $item[0]->orderStatus;
            $result[$i]['tableRow'] = $tableData;
            $result[$i]['childRow'] = $item;
            $i++;
        }
        return $result;
    }

    public function GetDailySales(Request $r){
        date_default_timezone_set('Asia/Dhaka');
        if($r->user_id != ''){
            $data['todaySell'] = DB::table('trns03a_issue_master')
                    ->where('trns03a_issue_master.created_by',$r->user_id)
                    ->whereDate('trns03a_issue_master.issue_date',Date('Y-m-d'))
                    ->sum('trns03a_issue_master.total_issue_amt_local_curr');

            $data['totalSum'] = DB::table('Get_POS_item_list')->where('user_id',$r->user_id)->sum('total');
        }else{
            $data['todaySell'] = 0;
		    $data['totalSum'] = 0;
        }
		if(Date('H') > 18){
			$data['showAfterEveningStatus'] = 'y';
		}else{
			$data['showAfterEveningStatus'] = 'n';
		}
        return $data;
    }

    public function EntranceGateSellQty(){
        date_default_timezone_set('Asia/Dhaka');
        $data['from_date'] = $from_date = date('Y-m-d');
        $data['to_date'] = $to_date = date('Y-m-d');
        $SellData = DB::table('trns03b_issue_child')
            ->leftJoin('trns03a_issue_master', 'trns03b_issue_child.issue_master_id', '=', 'trns03a_issue_master.id')
            ->whereDate('trns03a_issue_master.issue_date',Date('Y-m-d'))
            ->where('trns03b_issue_child.item_info_id','206')
            ->select('trns03b_issue_child.issue_qty as issue_qty','trns03b_issue_child.total_amount_local_curr as total_amount')
            ->get();
        

        $SellDataQty = collect($SellData);
        $data['sellQty'] = number_format($SellDataQty->sum('issue_qty'));
        $data['sellAmount'] = number_format($SellDataQty->sum('total_amount'),2);
        return $data;
    }
    public function GetDailyStoreWiseSalesReport(Request $r){
        date_default_timezone_set('Asia/Dhaka');
            if($r->date_val == ''){
                $dateVal = Date('Y-m-d');
            }else{
                $dateVal = Date('Y-m-d',strtotime($r->date_val));
            }
           
            if($r->user_id != ''){
                $data['todaySell'] =  DB::table('trns03b_issue_child AS ic')
                    ->leftJoin('trns03a_issue_master AS im', 'ic.issue_master_id','=','im.id')
                    ->leftJoin('var_item_info AS ii', 'ic.item_info_id','=','ii.id')
                    ->where('ic.created_by',$r->user_id)
                    ->whereDate('im.issue_date',$dateVal)
                    ->groupBy('ii.id')
                    ->select('ic.issue_rate','ii.display_itm_name',DB::raw('SUM(ic.issue_qty) as total_qty'),DB::raw('SUM(ic.item_value_tran_curr) as total_price'))
                    ->get();
                    $data['today_qty'] = intVal(collect($data['todaySell'])->sum('total_qty'));
                    $data['today_price'] = floatVal(collect($data['todaySell'])->sum('total_price'));
                    return $data;
            }else{
                $reportData =  DB::table('trns03b_issue_child AS ic')
                    ->leftJoin('trns03a_issue_master AS im', 'ic.issue_master_id','=','im.id')
                    ->leftJoin('var_item_info AS ii', 'ic.item_info_id','=','ii.id')
                    ->leftJoin('users AS user', 'im.created_by','=','user.id')
                    ->leftJoin('cs_company_store_location AS store', 'user.store_id','=','store.id')
                    ->whereDate('im.issue_date',$dateVal)
                    ->select('im.id as issue_master_id','im.created_by','user.store_id','store.sl_name','ic.issue_rate','ii.id as item_information_id','ii.display_itm_name','ic.issue_qty as issue_qty','ic.item_value_tran_curr as total_amount')
                    ->get();
                    $result = [];
                    $totalQty = 0;
                    $totalAmount = 0;
                    $storeWise = collect($reportData)->groupBy('store_id');
                    foreach($storeWise as $storeId=>$row){
                        $storeAmount = collect($row)->sum('total_amount');
                        $storeQty = collect($row)->sum('issue_qty');

                        $totalAmount += $storeAmount;
                        $totalQty += $storeQty;

                        $itemsData = collect($row)->groupBy('item_information_id');
                        $items = [];
                        foreach($itemsData as $item){
                            $itemAmount = collect($item)->sum('total_amount');
                            $itemQty = collect($item)->sum('issue_qty');
                            $items[] = [
                                'issue_master_id'=>$item[0]->issue_master_id,
                                'created_by'=>$item[0]->created_by,
                                'store_id'=>$item[0]->store_id,
                                'sl_name'=>$item[0]->sl_name,
                                'issue_rate'=>$item[0]->issue_rate,
                                'item_information_id'=>$item[0]->item_information_id,
                                'display_itm_name'=>$item[0]->display_itm_name,
                                'issue_qty'=>$itemQty,
                                'total_amount'=>$itemAmount,
                            ];
                        }


                        $rowData['store_id'] = $storeId;
                        $rowData['store_name'] = $row[0]->sl_name;
                        $rowData['store_qty'] = $storeQty;
                        $rowData['store_amount'] = $storeAmount;
                        $rowData['item_row'] =$items;
                        $result[] = $rowData;
                    }

                    return [
                        'todaySell'=>$result,
                        'today_qty'=>$totalQty,
                        'today_price'=>$totalAmount,
                    ];
            }
    }

    public function UserSalesSummeryPrinting(Request $r){
        date_default_timezone_set('Asia/Dhaka');

           $isStore = DB::table('tbl_user_sales_summary_printing_log')->insert([
                'user_id'=>$r->user_id,
                'store_id'=>$r->store_id,
                'total_online_sales'=>$r->total_online_sales,
                'total_local_sales'=>$r->total_local_sales,
                'grand_total'=>$r->grand_total,
                'log_time'=>Date('Y-m-d H:i:s'),
            ]);

            return [
                'status'=> $isStore,
                'message'=>'Success',
            ];
    }

    public function GetItemMasterGroupsList($userId){
        $res = DB::select('CALL GetUserWiseItemLayoutList("'.$userId.'")');

        $dataArr = Collect($res);
        $masterGroupsArr = $dataArr->groupBy('masterGroupId');
        $groupsArr = $dataArr->groupBy('GroupId');
        $masterGroupResult = [];
        $groupResult = [];
        $s = 0;
        foreach($masterGroupsArr as $masterGroupId=>$row){
            $masterGroupResult[$s]['masterGroupId'] =  $row[0]->masterGroupId;
            $masterGroupResult[$s]['masterGroup'] =  $row[0]->masterGroup;
            $masterGroupResult[$s]['masterGroupBn'] =  $row[0]->masterGroupBn;
            $s++;
        }

        $l = 0;
        foreach($groupsArr as $GroupId=>$row){
            $groupResult[$l]['masterGroupId'] =  $row[0]->masterGroupId;
            $groupResult[$l]['masterGroup'] =  $row[0]->masterGroup;
            $groupResult[$l]['masterGroupBn'] =  $row[0]->masterGroupBn;
            $groupResult[$l]['groupId'] =  $row[0]->GroupId;
            $groupResult[$l]['groupName'] =  $row[0]->GroupName;
            $groupResult[$l]['groupNameBn'] =  $row[0]->GroupName;
            $l++;
        }
        
        return [
            'masterGroups'=>$masterGroupResult,
            'groups'=>$groupResult,
            'subGroups'=>$dataArr,
        ];
    }

    public function GetRestaurentRoomWiseTableList($userId){
        $resData = DB::select('CALL GetRestuarentTableList("'.$userId.'")');
        $dataArr = collect($resData)->groupBy('roomId');
        $result = [];
        $i = 0;
        foreach($dataArr as $roomId=>$row){
            $result[$i]['roomId'] = $row[0]->roomId;
            $result[$i]['roomName'] = $row[0]->roomName;
            $result[$i]['roomNameBn'] = $row[0]->roomNameBn;
            $result[$i]['table_list'] = $row;
            $i++;
        }
        return $result;
    }

    public function CheckCustomerByPhoneNumber(Request $r){
            $custPhone = $r->cust_phone;
           $resData = DB::table('cs_customer_contact_info')
                ->leftJoin('cs_customer_details','cs_customer_contact_info.customer_id','=','cs_customer_details.id')
                ->where('cs_customer_contact_info.phone',$custPhone)
                ->select('cs_customer_details.id','cs_customer_details.customer_name','cs_customer_details.customer_name_bn','cs_customer_contact_info.phone')
                ->first();
            $result = [];
            if($resData){
                $result['resp_status'] = true;
                $result['customerId'] = $resData->id;
                $result['customerName'] = $resData->customer_name;
                $result['customerNameBn'] = $resData->customer_name_bn;
                $result['customerPhone'] = $resData->phone;
            }else{
                $result['resp_status'] = false;
                $result['customerId'] = '';
                $result['customerName'] = '';
                $result['customerNameBn'] = '';
                $result['customerPhone'] = '';
            }
            return $result;
    }

    public function getTransFile(Request $request){

        if($request->hasFile('transFile')){
            

             $fileName = date('ymdhis').'_'.time().'_'.$request->file('transFile')->getClientOriginalName();
            $dirPath = public_path('trns_file');
            $request->file('transFile')->move($dirPath, $fileName);
            return ['status'=>'success'];
        }else{
            return ['status'=>'failed'];
        }


    }




}
