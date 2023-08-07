<?php

use App\Models\IssueChild;
use App\Models\ProductCatagory;
use App\Models\TransactionSubType;
use App\Models\VarItemInfo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;


if (!function_exists('fiscalYearAndMonth')) {
    function fiscalYearAndMonth($date)
    {
        $currentDate = Carbon::parse($date);
        if ($currentDate->month >= 4) {
            $startYear = $currentDate->year;
            $endYear = $currentDate->year + 1;
        } else {
            $startYear = $currentDate->year - 1;
            $endYear = $currentDate->year;
        }
        $financialYear = $startYear . '-' . $endYear;
        $data = [
            'fiscal_year' => $financialYear,
            'vat_month' => $currentDate->format('F'),
        ];
        return $data;
    }
}
if (!function_exists('getNumber')) {
    function getNumber($tableName)
    {
        $prefix = substr($tableName, strpos($tableName, '_') + 1, strpos($tableName, '_', strpos($tableName, '_') + 1) - strpos($tableName, '_') - 1);
        $prefix = strtoupper($prefix);
        $number = $prefix . '-' . uniqid();
        return $number;
    }
}
if (!function_exists('issueNumber')) {
    function issueNumber()
    {
        $randomNumber = mt_rand(1, 99999);
        $formattedNumber = str_pad($randomNumber, 5, '0', STR_PAD_LEFT);
        $number = 'IM-' . now()->format('y') . '-' . $formattedNumber;
        return $number;
    }
}
if (!function_exists('chalanNumber')) {
    function chalanNumber()
    {
        $randomNumber = mt_rand(1, 99999);
        $formattedNumber = str_pad($randomNumber, 5, '0', STR_PAD_LEFT);
        $number = 'CH-' . now()->format('y') . '-' . $formattedNumber;
        return $number;
    }
}
if (!function_exists('sendJson')) {
    function sendJson($message, $data, $status)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status,
        ]);
    }
}
if (!function_exists('todaysSells')) {
    function todaysSells($id)
    {
        $tranSubType = TransactionSubType::where('tran_sub_type_name', 'like', 'Local Sells')->first();
        $today = Carbon::today();
        $totalsells = IssueChild::where('item_information_id', $id)
            ->whereDate('created_at', $today)
            // ->whereHas('itemInfo', function ($query) {
            //     $query->where('itm_sub_grp_id', ProductType::where('prod_type_name', 'like', 'Service')->id);
            // })
            ->sum('issue_qty');
        return $totalsells;
    }
}

if(!function_exists('sendResponse')){
    function sendResponse($data,$status=null){
        return response()->json($data, $status ??= 200);
    }
}
if (!function_exists ('varItemInfo')) {
    function varItemInfo(): Builder
    {
        return VarItemInfo::leftJoin('var_item_mapping_bin_prodtype','var_item_mapping_bin_prodtype.item_information_id','var_item_info.id')
        ->leftJoin('cs_company_store_location','cs_company_store_location.id','var_item_mapping_bin_prodtype.store_id')
        ->leftJoin('5m_sv_uom','5m_sv_uom.id','var_item_info.uom_id')
        ->leftJoin('r_floor','r_floor.store_id','cs_company_store_location.id')
        ->leftJoin('var_item_sub_group','var_item_sub_group.id','var_item_info.itm_sub_grp_id')
        ->leftJoin('var_item_group','var_item_group.id','var_item_sub_group.itm_grp_id')
        ->leftJoin('var_item_master_group','var_item_master_group.id','var_item_group.itm_mstr_grp_id')
        ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','var_item_master_group.prod_type_id')
        ->leftJoin('5h_sv_product_category','5h_sv_product_category.id','5f_sv_product_type.prod_cat_id')
        ->select(   'var_item_info.id as item_information_id',
                    'var_item_info.itm_sub_grp_id as itm_sub_grp_id',
                    'var_item_info.display_itm_name as display_item_name',
                    'var_item_info.uom_id as uom_id',
                    'var_item_info.current_rate as current_rate',
                    '5m_sv_uom.id as uom_id',
                    '5m_sv_uom.uom_short_code as uom_short_code',
                    'r_floor.id as floor_id',
                    'r_floor.floor_name as floor_name',
                    'var_item_sub_group.id as var_item_sub_group_id',
                    'var_item_sub_group.itm_sub_grp_des as itm_sub_grp_des',
                    'var_item_group.id as var_item_group_id',
                    'var_item_group.itm_grp_name as itm_grp_name',
                    'var_item_master_group.id as var_item_master_group_id',
                    'var_item_master_group.itm_mstr_grp_name as itm_mstr_grp_name',
                    '5f_sv_product_type.id as prod_type_id',
                    '5f_sv_product_type.prod_type_name as prod_type_name',
                    '5h_sv_product_category.id as prod_cat_id',
                    '5h_sv_product_category.prod_cat_name as prod_cat_name'
                );
    }
}

// generate category 
// if (!function_exists ('getProductTree')) {
//     function getProductTree(){
//         return ProductCatagory::select('id as key', 'prod_cat_name as title')->with('productTypes' => function($pt){
//             $pt->select('id as key','prod_type_name as title')->get();
//         })->get();
//     }

// }