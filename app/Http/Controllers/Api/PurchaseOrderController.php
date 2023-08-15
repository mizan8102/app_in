<?php

namespace App\Http\Controllers\Api;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CsCompanyStoreLocation;
use App\Models\ItemMasterGroup;
use App\Models\Paymode_type;
use App\Models\ProductCatagory;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderChild;
use App\Models\PurchaseReqChild;
use App\Models\PurchaseReqMaster;
use App\Models\PurchaseReqQty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search', '');
        $perPage = request('perPage', 10);

        $pending_requisition= ItemMasterGroup::select('var_item_master_group.*','5f_sv_product_type.prod_cat_id')
        ->with(['purchase_req' =>function($query){
            $query->select('*','trns00c_purchase_req_master.id as purchaseReq_mas_id',
            'cs_company_store_location.sl_name as store_name',
            DB::raw("DATE_FORMAT(requisition_date, '%d-%m-%Y') AS requisition_date"))
            ->leftJoin('cs_company_store_location','cs_company_store_location.id','trns00c_purchase_req_master.store_id')
            ->where('trns00c_purchase_req_master.is_active',1);
           
        }])
        ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','var_item_master_group.prod_type_id')
        ->where('5f_sv_product_type.id',2)
        ->get();
        $result=[];
        $i=0;
        foreach($pending_requisition as $pi){
            $result[$i]['master_group']= $pi->itm_mstr_grp_name;
            $result[$i]['master_group_id']= $pi->id;
            if($pi['purchase_req']){
                $result[$i]['data'] = collect($pi['purchase_req'])->pluck('purchaseReq_mas_id');
                $result[$i]['count'] = collect($pi['purchase_req'])->count();
                $result[$i]['requistions'] = $pi['purchase_req'];
            }else{
                $result[$i]['data'] = [];
                $result[$i]['count'] = 0;
                $result[$i]['requistions'] =[];
            }
            $i++;
        }

        // $item=PurchaseReqMaster::select('trns00c_purchase_req_master.*','var_item_master_group.itm_mstr_grp_name','5f_sv_product_type.prod_cat_id',
        // 'var_item_master_group.prod_type_id'
        // )
        // ->leftJoin('var_item_master_group','var_item_master_group.id','trns00c_purchase_req_master.master_group_id')
        // ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','var_item_master_group.prod_type_id')
        // ->where('trns00c_purchase_req_master.approved_status',0)
        // ->orderBy('var_item_master_group.id','ASC')->get();

        $po= PurchaseOrder::leftJoin('cs_supplier_details','cs_supplier_details.id','supplier_id')
        ->select('trns00e_purchase_order_master.*','cs_supplier_details.supplier_name')
        ->where('purchase_order_number','like', "%{$search}%")
        ->orderBy('trns00e_purchase_order_master.id','DESC')
        ->paginate($perPage);

        return sendResponse([
            "data"         => $po,
            "pendingOrder" => $result
        ],200);

    }
    // one view purchase order
    public function purchaseOrderOneView($id){
        return PurchaseOrder::with(['purchase_order_child' => function($query){
            $query->select('*',DB::raw('order_quantity*rate As lineTotal'))->leftJoin('var_item_info','trns00f_purchase_order_child.item_info_id','var_item_info.id');
        }
        ])
        ->select('cs_company_store_location.sl_name','cs_supplier_details.*','trns00e_purchase_order_master.*',
        '5f_sv_product_type.prod_type_name','5h_sv_product_category.prod_cat_name','var_item_master_group.itm_mstr_grp_name',
        '5x4_paymode_type.paymode_name')
        ->leftJoin('cs_company_store_location','cs_company_store_location.id','delivery_point')
        ->leftJoin('cs_supplier_details','cs_supplier_details.id','supplier_id')
        ->leftJoin('var_item_master_group','var_item_master_group.id','trns00e_purchase_order_master.master_group_id')
        ->leftjoin('5f_sv_product_type','5f_sv_product_type.id','var_item_master_group.prod_type_id')
        ->leftJoin('5h_sv_product_category','5h_sv_product_category.id','5f_sv_product_type.prod_cat_id')
        ->leftJoin('5x4_paymode_type','5x4_paymode_type.id','trns00e_purchase_order_master.pay_term')
        ->where('trns00e_purchase_order_master.id',$id)->get();
    }

    public function masterIdToGetTypeAndCategory($id){
        return ItemMasterGroup::select('5f_sv_product_type.*','5h_sv_product_category.prod_cat_name')
        ->leftjoin('5f_sv_product_type','5f_sv_product_type.id','var_item_master_group.prod_type_id')
        ->leftJoin('5h_sv_product_category','5h_sv_product_category.id','5f_sv_product_type.prod_cat_id')->where('var_item_master_group.id',$id)->first();
    }
    /**
     * deduction by rational
     *
     */

     public function changeDeducatedRational(Request $request){
        $all_id=$request;
        $id=$all_id[0];
        $sup_id=$all_id[1];
        $itm_id=$all_id[2];
        $order_qty=$all_id[3];
        $req_quat=$all_id[4];
        $pp_rational_quantity=$all_id[5];

        $data=PurchaseReqMaster::join('trns00d_purchase_req_child','trns00d_purchase_req_child.purchase_req_master_id','=','trns00c_purchase_req_master.id')
        ->leftJoin('purchase_order_mappings','purchase_order_mappings.purchase_req_child_id','=','trns00d_purchase_req_child.purchase_req_child_id')
        ->leftJoin('supplier_mappings','supplier_mappings.item_id','trns00d_purchase_req_child.item_information_id')
        ->select(
            'trns00d_purchase_req_child.purchase_req_child_id AS puChild',
            'trns00d_purchase_req_child.purchase_req_master_id','supplier_mappings.sup_id','trns00c_purchase_req_master.requisition_number',
            'trns00d_purchase_req_child.required_date',
            DB::raw('sum(trns00d_purchase_req_child.req_quantity) AS req_quantity'),
            DB::raw('sum(purchase_order_mappings.order_quantity) AS balace_qty'),

            )->whereIn('trns00d_purchase_req_child.purchase_req_master_id',$id)
        ->where('trns00d_purchase_req_child.item_information_id',$itm_id)
        ->where('supplier_mappings.sup_id',$sup_id)
        ->groupBy('trns00d_purchase_req_child.purchase_req_child_id')
        ->orderBy('req_quantity','asc')
        ->get();

        $result=[];
        $i=0;
        $sum=$order_qty;
        $numItems = count($data);
        foreach ($data as $key=>$item) {
                $result[$i]['purchase_req_master_id']=$item->purchase_req_master_id;
                $result[$i]['sup_id']=$item->sup_id;
                $result[$i]['requisition_number']=$item->requisition_number;
                $result[$i]['purchase_req_child_id']=$item->puChild;
                $result[$i]['required_date']=$item->required_date;
                if ($item->balace_qty) {
                    $result[$i]['balace_qty']=$item->balace_qty;
                    $result[$i]['req_quantity']=number_format((float)$item->req_quantity - $item->balace_qty, 3, '.', '');
                } else {
                    $result[$i]['req_quantity']=$item->req_quantity;
                }
                if($i === $numItems-1) {
                    $result[$i]['order_quantity']=$sum;
                  }
                else{
                    $result[$i]['order_quantity']=intval((float)($result[$i]['req_quantity']/$pp_rational_quantity)*$order_qty);
                    $sum=$sum-$result[$i]['order_quantity'];
                }

                $result[$i]['tmp_order_quantity']=$result[$i]['order_quantity'];

                $i++;
        }
        return $result;
     }
    /**
     * deducated by date
     */
    public function changeDeducatedDate(Request $request){
        $all_id=$request;
        $id=$all_id[0];
        $sup_id=$all_id[1];
        $itm_id=$all_id[2];
        $order_qty=$all_id[3];

        $data=PurchaseReqMaster::join('trns00d_purchase_req_child','trns00d_purchase_req_child.purchase_req_master_id','=','trns00c_purchase_req_master.id')
        ->leftJoin('purchase_order_mappings','purchase_order_mappings.purchase_req_child_id','=','trns00d_purchase_req_child.purchase_req_child_id')
        ->leftJoin('supplier_mappings','supplier_mappings.item_id','trns00d_purchase_req_child.item_information_id')
        ->select(
            'trns00d_purchase_req_child.purchase_req_child_id AS puChild',
            'trns00d_purchase_req_child.purchase_req_master_id','supplier_mappings.sup_id',
            'trns00c_purchase_req_master.requisition_number',
            'trns00d_purchase_req_child.required_date',
            DB::raw('sum(trns00d_purchase_req_child.req_quantity) AS req_quantity'),
            DB::raw('sum(purchase_order_mappings.order_quantity) AS balace_qty'),
        )->whereIn('trns00d_purchase_req_child.purchase_req_master_id',$id)
        ->where('trns00d_purchase_req_child.item_information_id',$itm_id)
        ->where('supplier_mappings.sup_id',$sup_id)
        ->groupBy('trns00d_purchase_req_child.purchase_req_child_id')
        ->orderBy('trns00d_purchase_req_child.required_date','asc')
        ->get();

        $result=[];
        $i=0;
        $sum=$order_qty;
        foreach ($data as $key=>$item) {
                $result[$i]['purchase_req_master_id']=$item->purchase_req_master_id;
                $result[$i]['sup_id']=$item->sup_id;
                $result[$i]['requisition_number']=$item->requisition_number;
                $result[$i]['purchase_req_child_id']=$item->puChild;
                $result[$i]['required_date']=$item->required_date;

                if ($item->balace_qty) {
                    $result[$i]['balace_qty']=number_format((float)$item->req_quantity - $item->balace_qty, 3, '.', '');
                    $result[$i]['req_quantity']=number_format((float)$item->req_quantity - $item->balace_qty, 3, '.', '');
                } else {
                    $result[$i]['balace_qty']=$item->req_quantity;
                    $result[$i]['req_quantity']=$item->req_quantity;
                }
                if($sum > $result[$i]['req_quantity']){
                    $result[$i]['order_quantity']=number_format((float)$result[$i]['req_quantity'],3, '.', '');
                }else{
                    $result[$i]['order_quantity']=number_format((float)$sum,3, '.', '');
                }
                $result[$i]['tmp_order_quantity']=$result[$i]['order_quantity'];
                $sum=$sum-$result[$i]['order_quantity'];
                $i++;
        }
        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create():void
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
            'date' => 'required',
            'delivery_date' => 'required',
            'supplier_id' => 'required|numeric',
            'delivery_point' => 'required|numeric|exists:cs_company_store_location,id|integer|min:1',
            'grp_master' => 'required|numeric|exists:var_item_master_group,id|integer|min:1',
            'item_row.*.item_information_id' => 'required|numeric|exists:var_item_info,id|integer|min:1',
            'item_row.*.order_quantity' => 'required',
            'item_row.*.rate' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'item_row.*.uom_id' => 'required|numeric|exists:5m_sv_uom,id',
            'item_row.*.req_list.*.requisition_number' => 'required|numeric|exists:trns00c_purchase_req_master,id|integer|min:1',
            'item_row.*.req_list.*.order_quantity' => 'required',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validated->errors()
            ], 422);
        }
        try {
            $orderMasterID =Helper::codeGenerate('trns00e_purchase_order_master');
           
            $requistion= collect($request->all_requisition_number)->pluck('requisition_number')->toArray();;
            $requistions_id= collect($request->all_requisition_number)->pluck('id')->toArray();;
            $orderMasterData =[
                    'purchase_req_master_id' => implode(", ", $requistion),
                    'purchase_order_number'  => $orderMasterID,
                    'company_id'             => Auth::user()->company_id,
                    'branch_id'              => Auth::user()->branch_id,
                    'store_id'               => Auth::user()->store_id,
                    'pay_term'               => $request->pay_term,
                    'purchase_order_date'    => date('Y-m-d',strtotime($request->date)) ,
                    'supplier_id'            => $request->supplier_id,
                    'delivery_point'         => $request->delivery_point,
                    'delivery_date'          => date('Y-m-d',strtotime($request->delivery_date)) ,
                    'master_group_id'        => $request->grp_master,
                    'remarks'                => $request->remarks,
                    'submitted_by'           => Auth::user()->id,
                    'recommended_by'         => Auth::user()->id,
                    'approved_by'            => Auth::user()->id,
                    'purchase_order_amount'  => $request->totalAmount,
                    'approved_status'        => "1",
                    'created_by'             => Auth::user()->id,
                    'created_at'             => date('Y-m-d H:i:s'),
                ];
            DB::beginTransaction();
            $data=PurchaseOrder::create($orderMasterData);
            foreach ($request->item_row as $item) {
                     $purchaseReq=[
                        'purchase_order_master_id' => $data->id,
                        'item_info_id' => $item['item_information_id'],
                        'uom_id' => $item['uom_id'],
                        'uom_short_code' => $item['uom_short_code'],
                        'relative_factor' => $item['relative_factor'],
                        'order_quantity' => $item['order_quantity'],
                        'recv_quantity' => '',
                        'required_date' => date("Y-m-d", strtotime($item['required_date'])),
                        'rate' => $item['rate'],
                        'total_amount_local_cr' =>$item['order_quantity']*$item['rate'] ,
                        'Remarks' => $item['Remarks'],
                        'Remarks_bn' => $item['Remarks'],
                        'created_by' => Auth::id()
                    ];
                    $purchase_child=PurchaseOrderChild::create($purchaseReq);
                    foreach($item['req_list'] as $distriubute_item){
                            $map_child=[
                                'purchase_order_master_id' => $data->id,
                                'purchase_req_child_id' =>$distriubute_item['Child_id'],
                                'item_info_id' =>$item['item_information_id'],
                                'order_quantity' =>$distriubute_item['order_quantity'],
                                'created_by' => Auth::id()
                            ];
                        PurchaseReqQty::create($map_child);
                    }
            }
            // close 
            foreach($requistions_id as $ids){
                if(!$this->purchaseReqClose($ids)){
                    PurchaseReqMaster::where('id',$ids)->update(['is_active'=>0]);
                }
            }

            DB::commit();
            return $data;
        }catch (\Exception $e) {
                DB::rollBack();
                return response()->json( $e->getMessage(),422);
            }
    }

    // purchase close 
    function purchaseReqClose($ids){
        $data = DB::select("CALL `PurchaseReqClose`(".$ids.")");
        foreach($data as $dd){
            if(intval($dd->balance_qty) > 0){
                return true;
            }
        }
        return false;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    public function status_close($id){
        return PurchaseOrder::where('id',$id)->update(['status' => 1]);  
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
        $raw_material=PurchaseReqChild::with(['supplier_mapping' => function($query) use($id) {
                    return $query->with('supplier_detail')->where('sup_id', '=', $id);
                }])
                ->leftJoin('var_item_info','var_item_info.id','=','trns00d_purchase_req_child.item_info_id')
                ->leftJoin('5m_sv_uom','var_item_info.uom_id','=','5m_sv_uom.id')
                ->leftJoin('supplier_mappings','supplier_mappings.item_id','trns00d_purchase_req_child.item_info_id')
                ->leftJoin('cs_supplier_details','cs_supplier_details.id','=','supplier_mappings.sup_id')
                ->select(
                    'var_item_info.display_itm_name',
                    'var_item_info.display_itm_name_bn',
                    'var_item_info.prod_type_id',
                    'var_item_info.uom_id',
                    '5m_sv_uom.uom_short_code',
                    '5m_sv_uom.relative_factor',
                    'cs_supplier_details.supplier_name',
                    'supplier_mappings.sup_id',
                    'trns00d_purchase_req_child.item_info_id as item_information_id',
                    'trns00d_purchase_req_child.required_date',
                    'trns00d_purchase_req_child.purchase_req_master_id',
                    'trns00d_purchase_req_child.requisition_number',
                    'trns00d_purchase_req_child.rate AS pu_rate',
                    'trns00d_purchase_req_child.id as Child_id',
                    'trns00d_purchase_req_child.req_quantity',
                )
                ->whereIn('trns00d_purchase_req_child.purchase_req_master_id',$request)
                ->where('supplier_mappings.sup_id',$id)
                ->orderBy('trns00d_purchase_req_child.required_date','ASC')
                ->get();
                // return $raw_material;
                $dd=collect($raw_material);
                $ddt=$dd->groupBy('item_information_id');
                $result=[];
                $i=0;
                foreach($ddt as $key=>$value){
                    $itemReqArr = [];
                    $dd=collect($value)->where('item_information_id',$key)->groupBy('Child_id');
                  
                    $sum=0;
                    foreach( $dd as $k=>$val){
                        $item_wise_prev_order_qty=PurchaseReqQty::where('item_info_id',$key)->where('purchase_req_child_id',$k)->sum('order_quantity');
                        $sum +=$item_wise_prev_order_qty;
                        foreach($val as $itemReqs){
                            $requisition=$itemReqs->req_quantity - $item_wise_prev_order_qty;
                            if(number_format((float)$requisition, 3, '.', '') != 0.000){
                                $data['main_req_qty_requisition'] = $itemReqs->req_quantity;
                                $data['requisition_numberId'] = $itemReqs->requisition_number;
                                $data['req_quantity'] =number_format((float)$itemReqs->req_quantity - $item_wise_prev_order_qty, 3, '.', '');
                                $data['tmp_order_quantity'] =number_format((float) $itemReqs->req_quantity - $item_wise_prev_order_qty, 3, '.', '');
                                $data['order_quantity'] =number_format((float) $itemReqs->req_quantity - $item_wise_prev_order_qty, 3, '.', '');
                                $data['mapping_order_quantity_complete'] = $item_wise_prev_order_qty;
                                $data['Child_id'] = $itemReqs->Child_id;
                                $data['requisition_number'] = $itemReqs->purchase_req_master_id;
                                $data['required_date'] =date('d-m-Y',strtotime($itemReqs->required_date));
                                $itemReqArr[] = $data;
                            }
                        }
                    }
                    $pre_order_quantity=$sum;
                    $purchase_req_master=collect($value)->groupBy('purchase_req_master_id');
                    $group_by_child=collect($value)->groupBy('Child_id')->values();
                    $totalRequisition=$purchase_req_master->count('purchase_req_master_id');
                    $req_quantity=collect($value)->sum('req_quantity');
                    $rr=number_format((float)$req_quantity, 3, '.', '');
                    $pre=number_format((float)$pre_order_quantity, 3, '.', '');
                    if ($rr != $pre) {
                        $result[$i]['purchase_req_master_id']=$value[0]->purchase_req_master_id;
                        $result[$i]['purchase_req_child_id']=$value[0]->Child_id;
                        $result[$i]['item_information_id']=$value[0]->item_information_id;
                        $result[$i]['uom_id']=$value[0]->uom_id;
                        $result[$i]['group_by_child']=$group_by_child;
                        $result[$i]['uom_short_code']=$value[0]->uom_short_code;
                        $result[$i]['relative_factor']=$value[0]->relative_factor;
                        $result[$i]['req_quantity']=$rr;
                        $result[$i]['purchase_req_master']=$purchase_req_master;
                        $result[$i]['rate']=$value[0]->pu_rate;
                        $result[$i]['balance_qty']=0.00;
                        $result[$i]['display_itm_name']=$value[0]->display_itm_name;
                        $result[$i]['display_itm_name_bn']=$value[0]->display_itm_name_bn;
                        $result[$i]['item_counter']=0;
                        $result[$i]['required_date']=date('d-m-Y',strtotime($value[0]->required_date));
                        $result[$i]['min_required_date']=$value[0]->required_date;
                        $result[$i]['isModal']=false;
                        $result[$i]['viewMode']=false;
                        if($pre_order_quantity){
                            $result[$i]['pre_order_quantity']=$pre;
                            $result[$i]['order_quantity']=number_format((float)($req_quantity - $pre_order_quantity), 3, '.', '');
                            $result[$i]['lineTotal']= (float) $value[0]->pu_rate * ($req_quantity - $pre_order_quantity);
                        }else{
                            $result[$i]['order_quantity']=number_format((float)$req_quantity, 3, '.', '');
                            $result[$i]['lineTotal']= number_format((float) ($value[0]->pu_rate * $req_quantity), 3, '.', '');
                            $result[$i]['pre_order_quantity']=0;
                        }
                        $result[$i]['pp_rational_quantity']=$result[$i]['order_quantity'];
                        $result[$i]['distribute']=true;
                        $result[$i]['sup_id']=$value[0]->sup_id;
                        $result[$i]['supplier_name']=$value[0]->supplier_name;
                        $result[$i]['totalRequisition']=$totalRequisition;
                        $result[$i]['Remarks']='';
                        $result[$i]['req_list']=$itemReqArr;
                        $result[$i]['supplier_mapping']=$value[0]->supplier_mapping;
                        $result[$i]['deducated']=[
                            "type" => '',
                        ];
                         $i++;
                    }
                }
        return response()->json([
            'raw_material' => $result
        ]);
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

    public function init(Request $request){
        $requisition_id=PurchaseReqMaster::select('requisition_number','id')->whereIn('id',$request)->get();
        $map_supplier=PurchaseReqChild::leftJoin('supplier_mappings','supplier_mappings.item_id','trns00d_purchase_req_child.item_info_id')
        ->join('cs_supplier_details','cs_supplier_details.id','=','supplier_mappings.sup_id')
        ->select('cs_supplier_details.supplier_name','cs_supplier_details.id')
        ->whereIn('trns00d_purchase_req_child.purchase_req_master_id',$request)->groupBy('supplier_mappings.sup_id')
        ->get();
        return response()->json([
            'map_supplier' => $map_supplier ,
            'requisition_numbers' => $requisition_id,
        ]);
    }

    public function ReadOnePurchaseOrder($id)
    {
        try {
            $data = DB::table('trns00e_purchase_order_master')
                ->join('purchase_order_mappings','trns00e_purchase_order_master.purchase_order_master_id','=','purchase_order_mappings.purchase_order_master_id')
                ->leftjoin('cs_employee_master','trns00e_purchase_order_master.submitted_by','=','cs_employee_master.employee_id')
                ->leftJoin('var_item_info','purchase_order_mappings.item_information_id','=','var_item_info.item_information_id')
               ->leftJoin('cs_supplier_details','trns00e_purchase_order_master.supplier_id','=','cs_supplier_details.supplier_id')
                ->select('trns00e_purchase_order_master.*','emp_name','purchase_order_mappings.*','var_item_info.display_itm_name_bn','cs_supplier_details.supplier_name_bn',)
                ->where('trns00e_purchase_order_master.purchase_order_master_id',$id)
                ->get();
            $result=[];
            $i=0;
            foreach ($data as $i=>$item) {
                if ($i == 0) {

                    $result['purchase_order_master_id']=$item->purchase_order_master_id;
                    $result['emp_name']=$item->emp_name;
                    $result['purchase_req_master_id']=$item->purchase_req_master_id;
                    $result['purchase_order_number']=$item->purchase_order_number;
                    $result['prod_type_id']=$item->prod_type_id;
                    $result['company_id']=$item->company_id;
                    $result['branch_id']=$item->branch_id;
                    $result['store_id']=$item->store_id;
                    $result['purchase_order_date']=$item->purchase_order_date;
                    $result['purchase_order_month']=$item->purchase_order_month;
                    $result['supplier_id']=$item->supplier_id;
                    $result['supplier_name_bn']=$item->supplier_name_bn;
                    $result['delivery_point']=$item->delivery_point;
                    $result['delivery_date']=$item->delivery_date;
                    $result['pay_term']=$item->pay_term;
                    $result['submitted_by']=$item->submitted_by;
                    $result['recommended_by']=$item->recommended_by;
                    $result['approved_by']=$item->approved_by;
                    $result['approved_status']=$item->approved_status;
                    $result['remarks']=$item->remarks;
                    $result['remarks_bn']=$item->remarks_bn;
                    $result['created_at']=$item->created_at;
                    $result['updated_at']=$item->updated_at;
                    $result['created_by']=$item->created_by;
                    $result['updated_by']=$item->updated_by;
                }
                $result['item_row'][$i]['purchase_order_child_id'] = $item->purchase_order_child_id;
                $result['item_row'][$i]['item_information_id'] = $item->item_information_id;
                $result['item_row'][$i]['display_itm_name_bn'] = $item->display_itm_name_bn;
                $result['item_row'][$i]['uom_id'] = $item->uom_id;
                $result['item_row'][$i]['uom_short_code'] = $item->uom_short_code;
                $result['item_row'][$i]['relative_factor'] = $item->relative_factor;
                $result['item_row'][$i]['lineTotal'] = $item->recv_quantity * $item->rate ;
                $result['item_row'][$i]['req_quantity'] = $item->recv_quantity;
                $result['item_row'][$i]['order_quantity'] = $item->recv_quantity;
                $result['item_row'][$i]['rate'] = $item->rate;
                $result['item_row'][$i]['total_amount_local_cr'] = $item->total_amount_local_cr;
                $result['item_row'][$i]['Remarks'] = $item->Remarks;
                $result['item_row'][$i]['Remarks_bn'] = $item->Remarks_bn;
                $result['item_row'][$i]['created_at'] = $item->created_at;
                $result['item_row'][$i]['updated_at'] = $item->updated_at;
                $result['item_row'][$i]['created_by'] = $item->created_by;
                $result['item_row'][$i]['updated_by'] = $item->updated_by;
            }
            return $result;
        }
        catch (\Exception $e) {
            return response()->json([
                "error"=> $e->getMessage(),
                "status" => 404,
             ]);
        }
    }

    /**
     * 
     */

    //  function getProductTree(){
        //         return ProductCatagory::select('id as key', 'prod_cat_name as title')->with('productTypes' => function($pt){
        //             $pt->select('id as key','prod_type_name as title')->get();
        //         })->get();
        //     }
    public function initForPurchaseReq(){
        $store=CsCompanyStoreLocation::select('id','sl_name')->get();
        $pro_cat=ProductCatagory::select('id','prod_cat_name')->get();

        $dd=ProductCatagory::with(['productTypes'=>function($query){
            $query->whereNot('id',3);
        },'productTypes.itemMasterGroups'])->get();
        // $dd= ProductCategoryResource::collection(ProductCatagory::with('productTypes','productTypes.itemMasterGroups')->get());
        $result=[];
        foreach ($dd as $key => $val) {
            $result[$key]['key']    = $key;
            $result[$key]['value']  = $val->id;
            $result[$key]['title']  = $val->prod_cat_name;
            $result[$key]['disabled']  = true;

            foreach ($val->productTypes as $pt_key => $pt) {
                $result[$key]['children'][$pt_key]['key']       = $key . '-' . $pt_key;
                $result[$key]['children'][$pt_key]['value']     = $pt->id;
                $result[$key]['children'][$pt_key]['title']     = $pt->prod_type_name;
                $result[$key]['children'][$pt_key]['disabled']  = true;
        
                foreach ($pt->itemMasterGroups as $m_key => $mm) {
                    $result[$key]['children'][$pt_key]['children'][$m_key]['key'] = $key . '-' . $pt_key . '-' . $m_key;
                    $result[$key]['children'][$pt_key]['children'][$m_key]['value'] = $mm->id;
                    $result[$key]['children'][$pt_key]['children'][$m_key]['title'] = $mm->itm_mstr_grp_name;
                }
            }
        }
        
        
        
        $payMode = Paymode_type::all();
        return Helper::sendJson([
            'store' => $store,
            'pro_catagroy'=> $pro_cat,
            'pay_mode' => $payMode,
            'tree' => $result,
        ]);
    }

    public function getPurchaseReqForOrder(){
        $search = request('search', '');
        $perPage = request('perPage', 10);
        $grpMaster= request('masterGroup');
        return  PurchaseReqMaster::
        select('trns00c_purchase_req_master.id as key','trns00c_purchase_req_master.requisition_number',
        'trns00c_purchase_req_master.requisition_date','var_item_master_group.itm_mstr_grp_name','users.name','trns00c_purchase_req_master.remarks')
        ->leftJoin('users','users.id','trns00c_purchase_req_master.submitted_by')
        ->leftJoin('var_item_master_group','var_item_master_group.id','master_group_id')
        ->where('trns00c_purchase_req_master.is_active',1)
        // ->where('requisition_number','like',"%{$search}%")
        ->where('var_item_master_group.id',$grpMaster)
        ->get();
    }
    
}
