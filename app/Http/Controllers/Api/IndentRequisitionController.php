<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ItemChildModel;
use App\Models\ItemMasterModel;
use App\Models\PProgramMaster;
use App\Models\PurchaseReqChild;
use App\Models\PurchaseReqMaster;
use App\Models\PurchaseRequisitionProdQty;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class IndentRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // list of all product requisition list
        $perPage = request('perPage', 10);
        $search = request('search');
        $master_group_id = request('master_group_id');
        $productRequisition = ItemMasterModel::where('product_req', 1)
            ->when($search, function ($query, $search) {
                return $query->where('id', 'LIKE', '%' . $search . '%')
                    ->orWhere('indent_number', 'LIKE', '%' . $search . '%');
            })
            ->when($master_group_id, function ($query, $master_group_id) {
                return $query->where('master_group_id', $master_group_id);
            })
            ->paginate($perPage);
        return response()->json([
            'message' => 'data found successfully',
            'data' => $productRequisition,
        ], 200);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = ItemChildModel::leftJoin('trns00d_purchase_req_child', 'trns00d_purchase_req_child.indent_child_id', '=', 'trns00b_indent_child.indent_child_id')
                ->leftJoin('var_item_info', 'trns00b_indent_child.item_info_id', '=', 'var_item_info.item_info_id')
                ->leftJoin('5m_sv_uom', 'var_item_info.uom_id', '=', '5m_sv_uom.uom_id')
                ->select(
                    'trns00b_indent_child.indent_child_id',
                    'trns00b_indent_child.uom_short_code',
                    'trns00b_indent_child.required_date',
                    'trns00b_indent_child.uom_id',
                    'trns00b_indent_child.indent_master_id',
                    'trns00b_indent_child.item_info_id',
                    'trns00b_indent_child.indent_quantity',
                    DB::raw('ROUND(trns00b_indent_child.indent_quantity,3) as req_quantity'),
                    DB::raw('ROUND(trns00b_indent_child.indent_quantity,3) as order_quantity'),
                    DB::raw('ROUND((ioc_rate * trns00b_indent_child.indent_quantity),3) AS lineTotal'),
                    DB::raw('ROUND(sum(trns00d_purchase_req_child.req_quantity),3) AS pre_req_quantity'),
                    'ioc_rate as rate',
                    'var_item_info.display_itm_name',
                    'var_item_info.display_itm_name_bn',
                    'trns00d_purchase_req_child.req_quantity AS PP',
                    'var_item_info.prod_type_id',
                    '5m_sv_uom.uom_id',
                    '5m_sv_uom.uom_short_code',
                    '5m_sv_uom.relative_factor',
                )->where('trns00b_indent_child.indent_master_id', $id)
                ->groupBy('trns00b_indent_child.item_info_id')
                ->get();
            $result = [];
            $i = 0;
            foreach ($data as $key => $item) {
                if ($item->indent_quantity != $item->pre_req_quantity) {
                    $result[$i]['indent_master_id'] = $item->indent_master_id;
                    $result[$i]['indent_child_id'] = $item->indent_child_id;
                    $result[$i]['item_info_id'] = $item->item_info_id;
                    $result[$i]['uom_id'] = $item->uom_id;
                    $result[$i]['uom_short_code'] = $item->uom_short_code;
                    $result[$i]['indent_quantity'] = $item->indent_quantity;
                    $result[$i]['consum_order_qty'] = $item->consum_order_qty;
                    $result[$i]['order_quantity'] = $item->order_quantity;
                    $result[$i]['pre_req_quantity'] = $item->pre_req_quantity;
                    if ($item->pre_req_quantity) {
                        $result[$i]['req_quantity'] = number_format((float)$item->indent_quantity - $item->pre_req_quantity, 3, '.', '');
                        $result[$i]['lineTotal'] = (float) $item->rate * ($item->indent_quantity - $item->pre_req_quantity);
                    } else {
                        $result[$i]['req_quantity'] = number_format((float) $item->req_quantity, 3, '.', '');
                        $result[$i]['lineTotal'] = (float) $item->rate * $item->req_quantity;
                    }
                    $result[$i]['rate'] = $item->rate;
                    $result[$i]['balance_qty'] = 0.00;
                    $result[$i]['display_itm_name'] = $item->display_itm_name;
                    $result[$i]['display_itm_name_bn'] = $item->display_itm_name_bn;
                    $result[$i]['prod_type_id'] = $item->prod_type_id;
                    $result[$i]['required_date'] = $item->required_date;
                    $i++;
                }
            }


            $prog = PProgramMaster::join('trns00a_indent_master', 'trns00a_indent_master.program_master_id', '=', 'p_program_master.id')
                ->select('program_name')->where('trns00a_indent_master.id', $id)->first();
            $prog_name = $prog->program_name;
            return response()->json([
                'result' => $result,
                'program' => $prog_name
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
    public function allPurchaseRequisiton()
    {
        $search = request('search', '');
        $perPage = request('perPage', 10);
        
        $item=ItemMasterModel::select('trns00a_indent_master.*','var_item_master_group.itm_mstr_grp_name','5f_sv_product_type.prod_cat_id',
        'var_item_master_group.prod_type_id'
        )
        ->leftJoin('var_item_master_group','var_item_master_group.id','master_group_id')
        ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','var_item_master_group.prod_type_id')
        ->where('trns00a_indent_master.product_req',1)->where('pro_req_close',0)
        ->orderBy('master_group_id','ASC')->get();

        
        $data=PurchaseReqMaster::leftJoin('users','users.id','submitted_by')
        ->leftJoin('var_item_master_group','trns00c_purchase_req_master.master_group_id','var_item_master_group.id')
        ->select('trns00c_purchase_req_master.id','trns00c_purchase_req_master.requisition_number','trns00c_purchase_req_master.requisition_date',
        'itm_mstr_grp_name','remarks','users.name')
        ->where('trns00c_purchase_req_master.is_active',1)
        ->where('requisition_number', 'like', "%{$search}%")->orderBy('trns00c_purchase_req_master.id','DESC')
        ->paginate($perPage);

        return response()->json([
            'data'  =>  $data,
            'item'  =>  Helper::master_group_wise_item_count($item)
        ]);
       
    }


    public function  closePurchaseRequisition($id){
        return PurchaseReqMaster::where('id',$id)->update(['is_active'=>0]);
    }

    public function getProductReqForPurchaseRequisition(){
        $search = request('search', '');
        $perPage = request('perPage', 10);
        $grpMaster= request('masterGroup');
        return ItemMasterModel::leftJoin('cs_company_store_location','cs_company_store_location.id','to_store_id')
        ->select(DB::raw("DATE_FORMAT(trns00a_indent_master.indent_date, '%d-%m-%Y') as indent_date_formate"),'trns00a_indent_master.*','cs_company_store_location.sl_name')
        ->where('trns00a_indent_master.product_req',1)
        ->where('pro_req_close',0)
        ->where('trns00a_indent_master.master_group_id',$grpMaster)
        // ->where('indent_number', 'like', "%{$search}%")
        ->orderBy('id','DESC')
        // ->paginate($perPage);
        ->get();
    }


    public function ReadOnePurchaseRequisition($id){
        return  PurchaseReqMaster::select('trns00c_purchase_req_master.*','users.name')
        ->with(['purchaseReqChild'=>function($fn){
            $fn->leftJoin('var_item_info','var_item_info.id','trns00d_purchase_req_child.item_info_id')
            ->leftJoin('5m_sv_uom','var_item_info.uom_id','=','5m_sv_uom.id')
            ->get();
        },'type'=>function($f){
            $f->with('catagory')->get();
        },'masterGroup'])->leftJoin('users','users.id','trns00c_purchase_req_master.submitted_by')
        ->find($id);
       
    }

    public function mergeProductRequisition(Request $request)
    {
        $identChild =ItemMasterModel::leftJoin('trns00b_indent_child','trns00a_indent_master.id','trns00b_indent_child.indent_master_id')
        ->leftJoin('var_item_info', 'var_item_info.id', 'trns00b_indent_child.item_info_id')
            ->leftJoin('5m_sv_uom', 'var_item_info.uom_id', '=', '5m_sv_uom.id')
            ->select(
                'trns00a_indent_master.indent_number',
                'trns00b_indent_child.uom_id',
                'trns00b_indent_child.id as Child_id',
                'trns00b_indent_child.item_info_id',
                'trns00b_indent_child.indent_master_id',
                'var_item_info.display_itm_name',
                'var_item_info.display_itm_name_bn',
                DB::raw("IFNULL(var_item_info.ioc_rate,0) as pu_rate"),
                'var_item_info.prod_type_id',
                'var_item_info.uom_id',
                '5m_sv_uom.uom_short_code',
                '5m_sv_uom.relative_factor',
                'trns00b_indent_child.uom_short_code',
                'trns00b_indent_child.indent_quantity',
                'trns00b_indent_child.required_date'
            )
            ->whereIn('indent_master_id', $request->indentNumber)
            ->get();
            $dd=collect($identChild);
            $ddt=$dd->groupBy('item_info_id');
            $result=[];
        $i = 0;
        foreach ($ddt as $key => $value) {
            $itemReqArr = [];
            $dd=collect($value)->where('item_info_id',$key)->groupBy('Child_id');
            $sum=0;
            foreach( $dd as $k=>$val){
                $item_wise_prev_order_qty=PurchaseRequisitionProdQty::where('item_info_id',$key)->where('indent_child_id',$k)->sum('requisition_quantity');
                $sum +=$item_wise_prev_order_qty;
                foreach($val as $itemReqs){
                    $requisition=$itemReqs->indent_quantity - $item_wise_prev_order_qty;
                    if(number_format((float)$requisition, 3, '.', '') != 0.000){
                        $data['main_indent_qty'] = $itemReqs->indent_quantity;
                        $data['indent_number'] = $itemReqs->indent_number;
                        $data['item_info_id'] =$value[0]->item_info_id;
                        $data['indent_quantity'] =number_format((float)$itemReqs->indent_quantity - $item_wise_prev_order_qty, 3, '.', '');
                        $data['tmp_requisition_quantity'] =number_format((float) $itemReqs->indent_quantity - $item_wise_prev_order_qty, 3, '.', '');
                        $data['req_quantity'] =number_format((float) $itemReqs->indent_quantity - $item_wise_prev_order_qty, 3, '.', '');
                        $data['mapping_requisition_quantity_complete'] = $item_wise_prev_order_qty;
                        $data['Child_id'] = $itemReqs->Child_id;
                        $data['required_date'] = date('d-m-Y', strtotime($itemReqs->required_date));
                        $itemReqArr[] = $data;
                    }
                }
            }
            $pre_req_quantity=$sum;
            $indent_quantity=collect($value)->sum('indent_quantity');
            $rr=number_format((float)$indent_quantity, 3, '.', '');
            $pre=number_format((float)$pre_req_quantity, 3, '.', '');
            if ($rr != $pre) {
                $result[$i]['viewMode']=false;
              
                if(intval($pre_req_quantity) > 0){
                    $result[$i]['pre_req_quantity']=$pre;
                    $result[$i]['req_quantity']=number_format((float)($indent_quantity - $pre_req_quantity), 3, '.', '');
                    $result[$i]['lineTotal']= (float) $value[0]->pu_rate * ($indent_quantity - $pre_req_quantity);
                }else{
                    $result[$i]['req_quantity']=number_format((float)$indent_quantity, 3, '.', '');
                    $result[$i]['lineTotal']= number_format((float) ($value[0]->pu_rate * $indent_quantity), 3, '.', '');
                    $result[$i]['pre_req_quantity']=0;
                }
                $result[$i]['pp_rational_quantity']=$result[$i]['req_quantity'];
                $result[$i]['distribute']=true;
                $result[$i]['balance_qty']=0.00;
                $result[$i]['item_info_id'] = $value[0]->item_info_id;
                $result[$i]['uom_id'] = $value[0]->uom_id;
                $result[$i]['display_itm_name'] = $value[0]->display_itm_name;
                $result[$i]['uom_short_code'] = $value[0]->uom_short_code;
                $result[$i]['indent_quantity'] = $indent_quantity;

                $result[$i]['required_date'] = date('d-m-Y');
                $result[$i]['remarks'] = "";
                $result[$i]['rate'] =$value[0]->pu_rate ;
                $result[$i]['Remarks']='';
                $result[$i]['req_list']=$itemReqArr;
                $result[$i]['deducated']=[
                    "type" => '',
                ];
                $i++;
            }

        }

        $indent_parent=ItemMasterModel::select('id','indent_number')->whereIn('id', $request->indentNumber)->get();
        return response()->json([
            'result' => $result,
            'parent_data' => $indent_parent
        ]);
    }
    public function storePurchaseRequisition(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|numeric|min:0|max:1',
            'date' => 'required',
            // 'required_date' => 'required|date',
            // 'remarks' => 'required|max:255',
            'remarks_bn' => 'sometimes|max:255',
            'item_row.*.item_info_id' => 'required|numeric|exists:var_item_info,id',
            'item_row.*.uom_id' => 'required|numeric|exists:5m_sv_uom,id',
            'item_row.*.rate' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'item_row.*.req_quantity' => 'required',
            'item_row.*.remarks' => 'sometimes|max:255',
            'item_row.*.remarks_bn' => 'sometimes|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        $comID = Auth::user()->company_id;
        $requisitionNumber =DB::select('CALL getTableID("trns00c_purchase_req_master","' . $comID . '")');
        $purchaseReqMaster = [
            'requisition_number' => $requisitionNumber[0]->masterID,
            'prod_type_id' => $request->pro_type,
            'master_group_id' => $request->grp_master,
            'company_id' => auth()->user()->company_id,
            'status' => $request->status ? 1 : 0,
            'is_partial' => 1,
            'branch_id' => auth()->user()->branch_id,
            'store_id' => auth()->user()->store_id,
            'requisition_date' =>date('Y-m-d',strtotime($request->date)),
            'submitted_by' => auth()->user()->id,
            'recommended_by' => auth()->user()->id,
            'approved_by' => auth()->user()->id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'approved_status' => 0,
            'remarks' => $request->remarks,
            'remarks_bn' => $request->remarks,
        ];
        try {
            DB::beginTransaction();
            $purchaseReq = PurchaseReqMaster::create($purchaseReqMaster);
            foreach ($request->item_row as  $data) {
                $req_child=PurchaseReqChild::create([
                    'purchase_req_master_id' => $purchaseReq->id,
                    'requisition_number' => $purchaseReq->requisition_number,
                    'item_info_id' => $data['item_info_id'],
                    'uom_id' => $data['uom_id'],
                    'rate' => $data['rate'],
                    'indent_quantity' => $data['indent_quantity'],
                    'req_quantity' => $data['req_quantity'],
                    'required_date' => date('Y-m-d',strtotime($data['required_date'])),
                    'Remarks' => $data['remarks'],
                    'remarks_bn' => $data['remarks'],
                ]);
                foreach($data['req_list'] as $distriubute_item){
                    $map_child=[
                        'purchase_Requisition_master_id' => $purchaseReq->id,
                        'indent_child_id' => $distriubute_item['Child_id'],
                        'requisition_quantity' => $distriubute_item['req_quantity'],
                        'item_info_id' => $distriubute_item['item_info_id'],
                        'created_by' => auth()->user()->id,
                        'updated_by' => auth()->user()->id,
                    ];
                PurchaseRequisitionProdQty::create($map_child);
            }
            }
           
            ItemMasterModel::whereIn('id',$request->req_ids)->update(['pro_req_close' => 1]);
            DB::commit();
            return response()->json([
                'message' => 'Purchase Requisition Created Successfully',
                'data' => $purchaseReq,
            ], 200);
        } catch (Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
