<?php

namespace App\Http\Controllers;

use App\Models\CsCompanyStoreLocation;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderQty;
use App\Models\ReceiveRawMaterial;
use App\Models\SupplierDetail;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ReceivedRawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prodId = '3';
        $search = request('search', '');
        $perPage = request('perPage', 10);

        return DB::table('trns02a_recv_master')
            ->leftJoin('5f_sv_product_type', 'trns02a_recv_master.prod_type_id', '=', '5f_sv_product_type.id')
            ->leftJoin('cs_supplier_details', 'trns02a_recv_master.supplier_id', '=', 'cs_supplier_details.id')
            ->where('trns02a_recv_master.prod_type_id', '=', $prodId)
            ->select(
                'trns02a_recv_master.id as receive_master_id',
                'trns02a_recv_master.purchase_order_master_id',
                'trns02a_recv_master.grn_number',
                'trns02a_recv_master.grn_date',
                'trns02a_recv_master.chalan_type',
                'trns02a_recv_master.chalan_number',
                'trns02a_recv_master.chalan_date',
                'trns02a_recv_master.prod_type_id',
                '5f_sv_product_type.prod_type_name',
                'cs_supplier_details.supplier_name',
                'trns02a_recv_master.total_recv_amt_local_curr',
                'trns02a_recv_master.total_vat_amount'
            )
            ->where('trns02a_recv_master.chalan_number', 'like', "%{$search}%")
            ->paginate($perPage);
    }

    /**
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
       
        $tansSrcTypeId      = 1;  // form Table 5c_sv_tran_source_type  receive=1
        $tranTypeID         = 1;
        $vatRebTypeID       = 2;
        $comID              = Auth::user()->company_id;
        $branchID           = Auth::user()->branch_id;
        $storeID            = Auth::user()->store_id;
        $currencyID         = 1;
        $supplierID         = $request->supplier_id;
        $auth               = Auth::user()->id;


        $newItemsArray = [];
        foreach ($request->item_row as $key => $value) {
            $newitem['item_info_id']            = $value['itemId'];
            $newitem['gate_rcv_qty']            = $value['recv_qty'];
            $newitem['uom_id']                  = $value['uom_id'];
            $newitem['uom_code']                = $value['uom_short_code'];
            $newitem['rel_factor']              = $value['relative_factor'];
            $newitem['vat_payment_method_id']   = 1;
            $newitem['item_cat_retail_id']      = 2;
            $newitem['gate_rcv_qty']            = $value['recv_qty'];
            $newitem['rcv_qty']                 = "";
            $newitem['rcv_adjt_qty']            = 0;
            $newitem['rcv_rate']                = $value['orderRate'];
            $newitem['rcv_ass_value_trans_curr']    = $value['lineTotal'];
            $newitem['rcv_value_wotax_trans_curr']  = $value['lineTotal'];
            $newitem['rcv_value_wotax_local_curr']  = $value['lineTotal'];
            $newitem['vat_rate_type_id']        = 1;
            $newitem['is_fixed_rate']           = 1;
            $newitem['fixed_rate']              = 1;
            $newitem['is_fixed_uom_id']         = 1;
            $newitem['cd_percent']              = 2;
            $newitem['cd_amount']               = 1;
            $newitem['rd_percent'] = 1;
            $newitem['rd_amount'] = 1;
            $newitem['sd_percent'] = 1;
            $newitem['sd_amount'] = 1;
            $newitem['vat_percent'] = 1;
            $newitem['vat_amount'] = 1;
            $newitem['at_percent'] = 1;
            $newitem['at_amount'] = 1;
            $newitem['ait_percent'] = 1;
            $newitem['ait_amount'] = 1;
            $newitem['total_amt_trans_curr'] = $value['lineTotal'];
            $newitem['total_amt_local_curr'] = $value['lineTotal'];
            $newitem['total_ass_value_local_curr'] = $value['lineTotal'];
            $newitem['gate_entry_at'] = now()->format('Y-m-d H:m:s');
            $newitem['gate_entry_by'] = 1;
            // $newitem['opening_stock_remarks'] = $value['remarks'] ?: null;
            $newItemsArray[] = $newitem;
        }
        $childArray = json_encode(['chileItem' => $newItemsArray]);

        try {
            DB::beginTransaction();
            $data = DB::select('CALL TRNS_RECEIVE_CREATEONE(:IssueMstrID,:tranSrcTypeID,:tranTypeID,:tranSubTypeID,:prodTypeId,:itemCatRetailID,:vatRebTypeID,:vatRateTypeID,:comID,:branchID,:storeID,:currencyID,:excgRate,:supplierID,:regStatus,:suppBinNum,:suppBinNumBn,:suppBankBranchID,:suppBankAcctType,:isRegBankTrans,:suppAcctNum,:fiscalYearID,:vatMonthID,:grnDate,:grnNo,:grnNoBn,:portDischargeID,:challanDate,:challanNum,:challanNumBn,:challanTypeID,:totalAssAmntTransCurr,:totalAssAmntLocalCurr,:totalAmntWOTaxTransCurr,:totalAmntWOTaxLocalCurr,:totalCDAmnt,:totalRDAmnt,:totalSDAmnt,:totalVATAmnt,:totalATAmnt,:totalAITAmnt,:totalAmntWithTaxTransCurr,:totalAmntWithTaxLocalCurr,:monthProcessStatus,:yearlyProcessStatus,:isVDSDone,:remarks,:remarksBn,:createdBy,:updatedBy,:chileItem)',
            [
                'IssueMstrID'               => "",
                'tranSrcTypeID'             => $tansSrcTypeId,
                'tranTypeID'                => $tranTypeID,
                'tranSubTypeID'             => "",
                'prodTypeId'                => 2,
                'itemCatRetailID'           => 1,
                'vatRebTypeID'              => $vatRebTypeID,
                'vatRateTypeID'             => 1,
                'comID'                     => $comID,
                'branchID'                  => $branchID,
                'storeID'                   => $storeID,
                'currencyID'                => $currencyID,
                'excgRate'                  => $currencyID,
                'supplierID'                => $supplierID,
                'regStatus'                 => 1,
                'suppBinNum'                => "",
                'suppBinNumBn'              => "",
                'suppBankBranchID'          => 1,
                'suppBankAcctType'          => 1,
                'isRegBankTrans'            => 1,
                'suppAcctNum'               => "",
                'fiscalYearID'              => 1,
                'vatMonthID'                => 1,
                'grnDate'                   => now()->format('Y-m-d'),
                'grnNo'                     => "0",
                'grnNoBn'                   => "0",
                'portDischargeID'           => 1,
                'challanDate'               => date('Y-m-d',strtotime($request->chalan_date)),
                'challanNum'                => 1,
                'challanNumBn'              => 1,
                'challanTypeID'             => 1,
                'totalAssAmntTransCurr'     => $request->totalAmount,
                'totalAssAmntLocalCurr'     => $request->totalAmount,
                'totalAmntWOTaxTransCurr'   => $request->totalAmount,
                'totalAmntWOTaxLocalCurr'   => $request->totalAmount,
                'totalCDAmnt'               => 1,
                'totalRDAmnt'               => 1,
                'totalSDAmnt'               => 1,
                'totalVATAmnt'              => 1,
                'totalATAmnt'               => 1,
                'totalAITAmnt'              => 1,
                'totalAmntWithTaxTransCurr' => $request->totalAmount,
                'totalAmntWithTaxLocalCurr' => $request->totalAmount,
                'monthProcessStatus'        => 1,
                'yearlyProcessStatus'       => 1,
                'isVDSDone'                 => 0,
                'remarks'                   => $request->remarks,
                'remarksBn'                 => $request->remarks,
                'createdBy'                 => $auth,
                'updatedBy'                 => $auth,
                'chileItem'                 => $childArray,
            ]
        );

            DB::commit();
            return response()->json('receive create successful', 200);
        } catch (Throwable $th) {
            return response()->json($th->getMessage(), 500);
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
        $data = ReceiveRawMaterial::leftJoin('trns02b_recv_child', 'trns02a_recv_master.id', '=', 'trns02b_recv_child.receive_master_id')
            ->leftJoin('5f_sv_product_type', 'trns02a_recv_master.prod_type_id', '=', '5f_sv_product_type.id')
            ->leftJoin('cs_supplier_details', 'trns02a_recv_master.supplier_id', '=', 'cs_supplier_details.id')
            ->leftJoin('var_item_info', 'trns02b_recv_child.item_information_id', '=', 'var_item_info.id')
            ->where('trns02a_recv_master.id', '=', $id)
            ->select(
                'trns02a_recv_master.*',
                'trns02b_recv_child.*',
                '5f_sv_product_type.prod_type_name',
                '5f_sv_product_type.prod_type_name_bn',
                'cs_supplier_details.supplier_name',
                'cs_supplier_details.supplier_name_bn',
                'trns02a_recv_master.total_recv_amt_local_curr',
                'trns02a_recv_master.total_vat_amount',
                'var_item_info.display_itm_name',
                'var_item_info.display_itm_name_bn'
            )
            ->get();

        $result = [];
        $i = 0;
        foreach ($data as $i => $item) {
            if ($i == 0) {
                $result['receive_master_id'] = $item->receive_master_id;
                $result['purchase_order_master_id'] = $item->purchase_order_master_id;
                $result['purchase_order_date'] = $item->purchase_order_date;
                $result['prod_type_id'] = $item->prod_type_id;
                $result['company_id'] = $item->company_id;
                $result['branch_id'] = $item->branch_id;
                $result['store_id'] = $item->store_id;
                $result['grn_number'] = $item->grn_number;
                $result['grn_date'] = $item->grn_date;
                $result['supplier_name_bn'] = $item->supplier_name_bn;
                $result['chalan_number'] = $item->chalan_number;
                $result['chalan_date'] = $item->chalan_date;
                $result['remarks'] = $item->remarks;
                $result['remarks_bn'] = $item->remarks_bn;
            }
            $result['item_row'][$i]['Receive_child_id'] = $item->Receive_child_id;
            $result['item_row'][$i]['item_information_id'] = $item->item_information_id;
            $result['item_row'][$i]['display_itm_name_bn'] = $item->display_itm_name_bn;
            $result['item_row'][$i]['uom_id'] = $item->uom_id;
            $result['item_row'][$i]['lineTotal'] = $item->itm_receive_rate * $item->rec_quantity;
            $result['item_row'][$i]['uom_short_code'] = $item->uom_short_code;
            $result['item_row'][$i]['rec_quantity'] = $item->rec_quantity;
            $result['item_row'][$i]['itm_receive_rate'] = $item->itm_receive_rate;
            // $result['item_row'][$i]['total_amount_local_curr'] = $item->total_amount_local_curr;
            $result['item_row'][$i]['created_at'] = $item->created_at;
            $result['item_row'][$i]['updated_at'] = $item->updated_at;
            $result['item_row'][$i]['created_by'] = $item->created_by;
            $result['item_row'][$i]['updated_by'] = $item->updated_by;
        }
        return $result;
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
        $raw_material = PurchaseOrder::leftJoin('trns00f_purchase_order_child', 'trns00f_purchase_order_child.purchase_order_master_id', '=', 'trns00e_purchase_order_master.purchase_order_master_id')
            ->leftJoin('var_item_info', 'var_item_info.item_information_id', '=', 'trns00f_purchase_order_child.item_information_id')
            ->leftJoin('cs_supplier_details', 'cs_supplier_details.id', '=', 'trns00e_purchase_order_master.supplier_id')
            ->select(
                'trns00e_purchase_order_master.purchase_order_master_id',
                'trns00e_purchase_order_master.purchase_order_number',
                'trns00e_purchase_order_master.purchase_order_date',
                'cs_supplier_details.supplier_name',
                'cs_supplier_details.id',

                'trns00f_purchase_order_child.purchase_order_child_id',
                'trns00f_purchase_order_child.item_information_id',
                'trns00f_purchase_order_child.uom_id',
                'trns00f_purchase_order_child.uom_short_code',
                'trns00f_purchase_order_child.order_quantity',
                'trns00f_purchase_order_child.required_date',
                'trns00f_purchase_order_child.rate',
                'trns00f_purchase_order_child.Remarks',
                'trns00f_purchase_order_child.Remarks_bn',


                'var_item_info.display_itm_name',
                'var_item_info.display_itm_name_bn',
                'var_item_info.prod_type_id',
            )->whereIn('trns00e_purchase_order_master.purchase_order_master_id', $request)
            ->where('cs_supplier_details.id', $id)
            ->get();
        $purchase_order_data = collect($raw_material)->groupBy('item_information_id');
        // return $purchase_order_data;

        $result = [];
        $i = 0;
        foreach ($purchase_order_data as $key => $value) {

            // for order list
            $itemPurArr = [];
            $sum = 0;
            $item_wise_others_value = collect($value)->where('item_information_id', $key)->groupBy('purchase_order_master_id');
            foreach ($item_wise_others_value as $k => $val) {

                $item_wise_prev_receive_qty = PurchaseOrderQty::where('item_information_id', $key)->where('purchase_order_master_id', $k)->sum('receive_quantity');
                $sum += $item_wise_prev_receive_qty;

                foreach ($val as $itmPurs) {
                    $order = $itmPurs->order_quantity - $item_wise_prev_receive_qty;
                    if ($order != 0) {
                        $itm['supplier_id'] = $itmPurs->supplier_id;
                        $itm['supplier_name'] = $itmPurs->supplier_name;
                        $itm['order_quantity'] = $itmPurs->order_quantity - $item_wise_prev_receive_qty;
                        $itm['receive_quantity'] = $itmPurs->order_quantity - $item_wise_prev_receive_qty;
                        $itm['tmp_receive_quantity'] = $itmPurs->order_quantity - $item_wise_prev_receive_qty;
                        $itm['item_information_id'] = $itmPurs->item_information_id;
                        $itm['purchase_order_master_id'] = $itmPurs->purchase_order_master_id;
                        $itm['purchase_order_number'] = $itmPurs->purchase_order_number;
                        $itm['required_date'] = date('d-m-Y', strtotime($itmPurs->required_date));

                        $itemPurArr[] = $itm;
                    }
                }
            }
            $pre_received_qty = $sum;
            //
            $order_quantity = collect($value)->sum('order_quantity');
            if ($pre_received_qty != $order_quantity) {
                $result[$i]['item_information_id'] = $value[0]->item_information_id;
                $result[$i]['display_itm_name'] = $value[0]->display_itm_name;
                $result[$i]['uom_id'] = $value[0]->uom_id;
                $result[$i]['uom_short_code'] = $value[0]->uom_short_code;
                $result[$i]['order_quantity'] = $order_quantity;
                $result[$i]['required_date'] = date('d-m-Y', strtotime($value[0]->required_date));
                $result[$i]['rate'] = $value[0]->rate;
                $result[$i]['po_rate'] = $value[0]->rate;
                $result[$i]['balance_qty'] = 0.00;
                $result[$i]['order_list'] = $itemPurArr;
                if ($pre_received_qty) {
                    $result[$i]['pre_received_qty'] = $pre_received_qty;
                    $result[$i]['receive_quantity'] = number_format((float)($order_quantity - $pre_received_qty), 3, '.', '');
                    $result[$i]['lineTotal'] = number_format((float)$value[0]->rate * ($order_quantity - $pre_received_qty), 3, '.', '');
                } else {
                    $result[$i]['receive_quantity'] = number_format((float)$order_quantity, 3, '.', '');
                    $result[$i]['lineTotal'] =  number_format((float)($value[0]->rate * $order_quantity), 3, '.', '');
                    $result[$i]['pre_received_qty'] = 0;
                }
                $result[$i]['pp_rational_quantity'] = number_format((float)$result[$i]['receive_quantity'], 3, '.', '');
                $result[$i]['isModal'] = false;
                $result[$i]['viewMode'] = false;
                $result[$i]['distribute'] = true;
                $result[$i]['deducated'] = [
                    "type" => '',
                ];
                $i++;
            }
        }
        return $result;
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

    // purchase receive chalan  number
    public function chalanNumber()
    {
        $latest = ReceiveRawMaterial::latest()->select('id')->orderBy('id', 'Desc')->first();
        if (!$latest) {
            return 'PRMC23-0001';
        }
        $string = preg_replace("/[^0-9\.]/", '', $latest->id);
        $eid = 'PRMC23-' . sprintf('%04d', $string + 1);
        return $eid;
    }


    // initialize data
    public function init()
    {
        $single = request()->input('id');
        $paginate = request()->input('perPage', 10);
        $search = request()->input('search');

        $purchaseOrders = PurchaseOrder::join('cs_supplier_details', 'trns00e_purchase_order_master.supplier_id', '=', 'cs_supplier_details.id')
            ->join('cs_company_store_location', 'trns00e_purchase_order_master.delivery_point', '=', 'cs_company_store_location.id')
            ->select(
                'trns00e_purchase_order_master.id',
                'trns00e_purchase_order_master.purchase_order_number',
                'trns00e_purchase_order_master.purchase_order_date',
                'trns00e_purchase_order_master.delivery_date',
                'cs_supplier_details.supplier_name',
                'cs_supplier_details.id AS supplier_id',
                'cs_company_store_location.sl_name AS delivery_point',
                'cs_company_store_location.id AS delivery_point_id',
            )
            ->when($search, function ($query, $search) {
                return $query->where('trns00e_purchase_order_master.purchase_order_number', 'LIKE', "%$search%")
                    ->orWhere('cs_supplier_details.supplier_name', 'LIKE', "%$search%");
            })
            ->paginate($paginate);


        return sendJson('Found the purchase order', $purchaseOrders, 200);
    }
    public function initSingle($id)
    {
        $purchaseOrders = PurchaseOrder::with('supplierDetail', 'store', 'purchase_order_child', 'purchase_order_child.itemInfo.sv_uom')->find($id);
        $combinedData = [];
        $i = 0;
        foreach ($purchaseOrders->purchase_order_child as $childData) {
            $combinedData[$i]['purchase_order_child_id'] = $childData->id;
            $combinedData[$i]['item_information_id'] = $childData->item_information_id;
            $combinedData[$i]['display_itm_name'] = $childData->itemInfo[0]->display_itm_name;
            $combinedData[$i]['uom_id'] = $childData->uom_id;
            $combinedData[$i]['uom_short_code'] = $childData->uom_short_code;
            $combinedData[$i]['order_quantity'] = $childData->order_quantity;
            $combinedData[$i]['recv_quantity'] = $childData->recv_quantity;
            $combinedData[$i]['rate'] = $childData->rate;
            $combinedData[$i]['total_amount_local_cr'] = $childData->total_amount_local_cr;
            $combinedData[$i]['required_date'] = $childData->required_date;
            $combinedData[$i]['rate'] = $childData->rate;
            $combinedData[$i]['rate'] = $childData->rate;
            $combinedData[$i]['order_list'] = $childData->itemInfo[0]->itemPurchaseOrder;
            $i++;
            // $childData->itemInfo[0]->itemPurchaseOrder
        }
        $purchaseOrder = [
            'purchase_order_id' => $purchaseOrders->id,
            'purchase_req_master_id' => $purchaseOrders->purchase_req_master_id,
            'purchase_order_number' => $purchaseOrders->purchase_order_number,
            'purchase_order_date' => $purchaseOrders->purchase_order_date,
            'purchase_order_month' => $purchaseOrders->purchase_order_month,
            'supplier_id' => $purchaseOrders->supplier_id,
            'supplier_name' => SupplierDetail::find($purchaseOrders->supplier_id)->supplier_name,
            'delivery_point' => CsCompanyStoreLocation::find($purchaseOrders->delivery_point)->sl_name,
            'delivery_point_id' => $purchaseOrders->delivery_point,
            'delivery_date' => $purchaseOrders->delivery_date,
            'supplier_id' => $purchaseOrders->supplier_id,
            'item_row' => $combinedData,
        ];


        return sendJson('Found the purchase order', $purchaseOrder, 200);
    }
}
