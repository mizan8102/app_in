<?php

namespace App\Http\Controllers\OpeningStock;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\ItemMasterGroup;
use App\Models\ItemStockChild;
use App\Models\ItemStockMaster;
use App\Models\ProductGroup;
use App\Models\ProductType;
use App\Models\RecvChild;
use App\Models\RecvMaster;
use App\Models\SubGroup;
use App\Models\VarItemInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class OpeningStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPage = request('perPage', 10);
        $search = request('search', '');
        $dateRange = (int) request('days', 30);
        if ($dateRange === 7 || $dateRange === 10 || $dateRange === 30) {
            $startDate = Carbon::now()->subDays($dateRange);
            $endDate = Carbon::now();
        } elseif ($dateRange >= 90) {
            $endDate = Carbon::now()->endOfMonth();
            $startDate = $endDate->copy()->subDays(min($endDate->day - 1, 89));
        }
        $itemStockMaster = RecvMaster::leftJoin('cs_company_store_location',  'cs_company_store_location.id', '=', 'trns02a_recv_master.store_id')
            ->where('tran_source_type_id', 3)

            ->select('trns02a_recv_master.id as id', 'trns02a_recv_master.grn_number', 'purchase_order_date as opening_bal_date', 

            'trns02a_recv_master.store_id', 'total_receive_amount as opening_bal_amount', 
            'cs_company_store_location.sl_name as store_name',
             DB::raw('(SELECT SUM(recv_quantity) FROM trns02b_recv_child WHERE receive_master_id = trns02a_recv_master.id) as total_quantity'))
            ->orderByDesc('trns02a_recv_master.id')
            ->where('trns02a_recv_master.store_id',Auth::user()->store_id)
            ->where('cs_company_store_location.sl_name', 'like', "%{$search}%")
            ->paginate($perPage);
        return $itemStockMaster;
    }
    /**
     * initilize data
     */
    public function init(Request $request)
    {
        $catagory = $request->catagory;
        $type = $request->type;
        $master_group = $request->master_group;
        $group = $request->group;
        $subgroup = $request->sub_group;
        $pro_type = [];
        $pro_master_group = [];
        $pro_group = [];
        $pro_sub_group = [];
        $item = array();
        if (!empty($catagory) && empty($type) && empty($master_group) && empty($group) && empty($subgroup)) {
            $pro_type = ProductType::where('prod_cat_id', $catagory)->get();
        } elseif (!empty($catagory) && !empty($type) && empty($master_group) && empty($group) && empty($subgroup)) {
            $pro_type = ProductType::where('prod_cat_id', $catagory)->get();
            $pro_master_group = ItemMasterGroup::where('prod_type_id', $type)->get();
        } elseif (!empty($catagory) && !empty($type) && !empty($master_group) && empty($group) && empty($subgroup)) {
            $pro_type = ProductType::where('prod_cat_id', $catagory)->get();
            $pro_master_group = ItemMasterGroup::where('prod_type_id', $type)->get();
            $pro_group = ProductGroup::where('itm_mstr_grp_id', $master_group)->get();
        } elseif (!empty($catagory) && !empty($type) && !empty($master_group) && !empty($group) && empty($subgroup)) {
            $pro_type = ProductType::where('prod_cat_id', $catagory)->get();
            $pro_master_group = ItemMasterGroup::where('prod_type_id', $type)->get();
            $pro_group = ProductGroup::where('itm_mstr_grp_id', $master_group)->get();
            $pro_sub_group = SubGroup::where('itm_grp_id', $group)->get();
        } elseif (!empty($catagory) && !empty($type) && !empty($master_group) && !empty($group) && !empty($subgroup)) {
            $pro_type = ProductType::where('prod_cat_id', $catagory)->get();
            $pro_master_group = ItemMasterGroup::where('prod_type_id', $type)->get();
            $pro_group = ProductGroup::where('itm_mstr_grp_id', $master_group)->get();
            $pro_sub_group = SubGroup::where('itm_grp_id', $group)->get();
        }

        // $fiscal = fiscalYearAndMonth(now());
        return response()->json([
            'opening_bal_date' => now()->format('Y-m-d'),
            'currency' =>  Currency::orderBy('currency_shortcode')->get(),
            'pro_type' => $pro_type,
            'pro_master_group' => $pro_master_group,
            'pro_group' => $pro_group,
            'pro_sub_group' => $pro_sub_group,
            'item' => $item,
        ]);
    }

    /**
     * item get
     */
    public  function itemGet(Request $request){
        $subgroup=$request->subgroup;
        $selected=$request->selected;
        return VarItemInfo::with('sub_group', 'sub_group.product_group')
            ->leftJoin('5m_sv_uom', '5m_sv_uom.id', 'var_item_info.uom_id')
            ->select('var_item_info.*', 'var_item_info.uom_id', '5m_sv_uom.uom_short_code')
            ->where('itm_sub_grp_id', $subgroup)
            ->where('var_item_info.is_active',1)
            ->whereNotIn('var_item_info.id',$selected)
            ->get();
    }
    /**
     *pu
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'opening_bal_date' => 'required|date|before_or_equal:now',
            'fiscal_year' => 'required|regex:/^\d{4}-\d{4}$/',
            // 'vat_month' => 'required|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'currency' => 'required|numeric|exists:5n_sv_currency_info,id',
            'receive_issue_date' => 'required|date|after_or_equal:opening_bal_date',
            'store_id' => 'required|numeric|exists:cs_company_store_location,id|integer|min:1|max:50',
//            'pro_catagory' => 'required|numeric|exists:5h_sv_product_category,id|integer|min:1|max:50',
//            'prod_type_id' => 'required|numeric|exists:5f_sv_product_type,id|integer|min:1|max:50',
            'item_row.*.opening_bal_qty' => 'required|numeric',
            'item_row.*.opening_bal_amount' => 'required|numeric',
            'item_row.*.opening_bal_rate' => 'required|numeric',
            'item_row.*uom_id' => 'required|numeric|exists:5m_sv_uom,id'
        ]);
        $validated->after(function ($validated) use ($request) {
            $itemRows = $request->input('item_row');
            $uniqueErrors = [];
            foreach ($itemRows as $index => $itemRow) {
                $itemInformationId = $itemRow['item_information_id'];
                $issueChildExists = DB::table('trns03b_issue_child')
                    ->leftJoin('trns03a_issue_master','trns03a_issue_master.id','trns03b_issue_child.issue_master_id')
                    ->where('item_info_id', $itemInformationId)
                    ->where('trns03a_issue_master.store_id',Auth::user()->store_id)
                    ->first();
                $recvChildExists = DB::table('trns02b_recv_child')
                    ->leftJoin('trns02a_recv_master','trns02b_recv_child.receive_master_id','trns02a_recv_master.id')
                    ->where('item_info_id', $itemInformationId)
                    ->where('trns02a_recv_master.store_id',Auth::user()->store_id)
                    ->first();
                $itemStockMasterExists = DB::table('trns_itemstock_master')
                    ->leftJoin('trns_itemstock_child','trns_itemstock_child.itemstock_master_id','trns_itemstock_master.id')
                    ->where('item_info_id', $itemInformationId)
                    ->where('trns_itemstock_child.store_id',Auth::user()->store_id)
                    ->first();
                if ($issueChildExists || $recvChildExists || $itemStockMasterExists) {
                    $itemInformationId = $itemRow['item_information_id'];
                    // in the issue  or receive  or opening balance. ID: {$displayItemName->id}
                    $displayItemName = VarIteminfo::find($itemRow['item_information_id']);
                    $uniqueErrors[$index]['message'] = "The item {$displayItemName->display_itm_name}  is already added ";
                }

            }
            foreach ($uniqueErrors as $index => $uniqueError) {
                $validated->errors()->add("item_row.{$index}.item_information_id.", $uniqueError['message']);
            }
        });
        if ($validated->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validated->errors()
            ], 422);
        }
        try {
            $branch_id = auth()->user()->branch_id;
            $created_by = auth()->user()->id;
            $company_id = auth()->user()->company_id;
            $store_id = auth()->user()->store_id;
            $grnNumber=Helper::codeGenerate('grn_number');
            $sum = 0;
            $sum=collect($request->item_row)->sum('opening_bal_amount');

//            foreach ($request->item_row as $child_data) {
//                $sum += $child_data['opening_bal_amount'];
//            }
            // $trans_sourc_type_id=TransactionSourceType::where('tran_source_type_name', 'Opening Balance')->first()->id;
            $trans_sourc_type_id=3;
            $opening_balance_date=date("Y-m-d", strtotime($request->opening_bal_date));
            $fiscal_year_id=$request->fiscal_id;
            $vat_month_id=$request->vat_month_id;
            DB::beginTransaction();
            $recvMaster=RecvMaster::create([
                'purchase_order_date' => $opening_balance_date,
                'tran_source_type_id' => $trans_sourc_type_id,
                'tran_type_id' => $trans_sourc_type_id,
                'prod_type_id' => $request->prod_type_id,
                'company_id' => $company_id,
                'store_id' => $store_id,
                'branch_id' => $branch_id,
                'fiscal_year_id' => $fiscal_year_id ,// need to check backend
                'vat_month_id' =>$vat_month_id,
                'currency_id' => $request->currency, // need to check backend
                'excg_rate' => 1, // need to check backend
                'receive_date' => $opening_balance_date,
                'duty_chalan_date' => $opening_balance_date,
                'total_receive_amount' => $sum,
                'total_recv_amt_local_curr' => $sum,
                'created_by' => $created_by,
                'grn_number' => $grnNumber,
                'grn_number_bn' => $grnNumber,
                'grn_date' => $opening_balance_date,
                'updated_by' => '',
            ]);
            foreach ($request->item_row as $item) {
                $recv_child=RecvChild::create([
                    'receive_master_id' => $recvMaster->id,
                    'item_info_id' => $item['item_information_id'],
                    'uom_id' => $item['uom_id'],
                    'uom_short_code' =>$item['uom_short_code'],
                    'po_qty' => $item['opening_bal_qty'],
                    'po_rate' => $item['opening_bal_rate'],
                    'recv_quantity' => $item['opening_bal_qty'],
                    'itm_receive_rate' => $item['opening_bal_rate'],
                    'total_amount_local_curr' => $item['opening_bal_amount'],
                    'opening_stock_remarks' => $item['remarks'],
                    'created_by' => $created_by,
                ]);

                $itemStockMaster=ItemStockMaster::create([
                    'opening_bal_date' => $opening_balance_date,
                    'receive_Issue_master_id' => $recvMaster->id,
                    'receive_issue_date' => date("Y-m-d", strtotime($request->receive_issue_date)),
                    'prod_type_id' => $request->prod_type_id,
                    'tran_source_type_id' => $trans_sourc_type_id,
                    'tran_type_id' => $trans_sourc_type_id,
                    'company_id' => $company_id,
                    'branch_id' => $branch_id,
                    'uom_id' => $item['uom_id'],
                    'fiscal_year_id' => $fiscal_year_id,
                    'currency_id' => $request->currency,
                    'vat_month_id' => $vat_month_id,
                    'item_info_id' => $item['item_information_id'],
                    'created_by' => $created_by,
                    'updated_by' => ''
                ]);
                $itemStockChild=ItemStockChild::create([
                    'store_id' => $store_id,
                    'itemstock_master_id' => $itemStockMaster->id,
                    'receive_issue_child_id' => $recv_child->id,
                    'opening_bal_qty' => $item['opening_bal_qty'],
                    'closing_bal_qty' => $item['opening_bal_qty'],
                    'opening_bal_rate' => $item['opening_bal_rate'],
                    'closing_bal_rate' => $item['opening_bal_rate'],
                    'opening_bal_amount' => $item['opening_bal_amount'],
                    'closing_bal_amount' => $item['opening_bal_amount'],
                    'created_by' => $created_by,
                    'updated_by' => ''
                ]);
            }
            DB::commit();
            return $recvMaster;
        } catch (\PHPUnit\Exception $ex) {
            DB::rollback();
            return response([
                'message' => $ex->getMessage(),
                'status' => '400'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return  RecvMaster::select('trns02a_recv_master.*','5x2_vat_month_info.vm_info as vat_month','5x1_fiscal_year_info.fsc_year_info')
        ->with(['recvChild'=>function($query){
            $query->leftJoin('var_item_info','var_item_info.id','trns02b_recv_child.item_info_id')
                ->leftJoin('trns_itemstock_master','var_item_info.id','trns_itemstock_master.item_info_id')
                ->leftJoin('trns_itemstock_child','trns_itemstock_child.itemstock_master_id','trns_itemstock_master.id')
                ->leftJoin('var_item_sub_group','var_item_info.itm_sub_grp_id','var_item_sub_group.id')
                ->leftJoin('var_item_group','var_item_group.id','var_item_sub_group.itm_grp_id')
                ->leftJoin('5m_sv_uom','var_item_info.uom_id','=','5m_sv_uom.id')
                ->where('trns_itemstock_child.store_id',Auth::user()->store_id)
                ->get();
        },'itemStock'=>function($a){
            $a->leftJoin('5n_sv_currency_info','5n_sv_currency_info.id','trns_itemstock_master.currency_id')->first();
        },'store','itemStock.currency'])
        ->leftJoin('5x2_vat_month_info','5x2_vat_month_info.id','trns02a_recv_master.vat_month_id')
        ->leftJoin('5x1_fiscal_year_info','5x1_fiscal_year_info.id','trns02a_recv_master.fiscal_year_id')
//            ->where('trns02a_recv_master.store_id',Auth::user()->store_id)
            ->where('trns02a_recv_master.id',$id)->first();
    }
    /**
     * report opening stock
     */
    public function initOpenstockReport()
    {
        return ProductGroup::all();
    }

    public function openStockReport(Request $request)
    {
        return ProductGroup::with(['sub_group' => function ($query) {
            $query->with(['var_item_info' => function ($query) {
                $query->rightJoin('trns_itemstock_master', 'trns_itemstock_master.item_information_id', 'var_item_info.item_information_id')
                    ->leftJoin('trns_itemstock_child', 'trns_itemstock_child.itemstock_master_id', 'trns_itemstock_master.itemstock_master_id')
                    ->leftJoin('5m_sv_uom', 'var_item_info.id', '=', '5m_sv_uom.id')
                    ->leftJoin('cs_company_store_location', 'trns_itemstock_child.store_id', 'cs_company_store_location.store_id')
                    ->select(
                        DB::raw('sum(trns_itemstock_child.opening_bal_qty) As group_by_total_qty'),
                        'trns_itemstock_master.*',
                        'var_item_info.*',
                        'trns_itemstock_child.*',
                        '5m_sv_uom.*',
                        'cs_company_store_location.*'
                    )
                    ->groupBy('trns_itemstock_master.item_information_id')->get();
            }]);
        }])
            ->where('itm_grp_id', $request->searchInput)
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

    }
}