<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CsCompanyStoreLocation;
use App\Models\IndentChildren;
use App\Models\ItemChildModel;
use App\Models\ItemMasterGroup;
use App\Models\ItemMasterModel;
use App\Models\ProductCatagory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $item=ItemMasterGroup::select('trns00a_indent_master.*','var_item_master_group.itm_mstr_grp_name','5f_sv_product_type.prod_cat_id',
        'var_item_master_group.prod_type_id'
        )
        ->leftJoin('trns00a_indent_master','var_item_master_group.id','trns00a_indent_master.master_group_id')
        ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','var_item_master_group.prod_type_id')
        ->where('trns00a_indent_master.product_req',0)
        ->where('close_status',0) ->where('pro_req_close',0)
        ->orderBy('master_group_id','ASC')->get();
        $result=collect($item);
        $grouped = $result->groupBy('itm_mstr_grp_name')->map(function ($items, $master_group_id) {
           
            
            return [
                'master_group' => $master_group_id,
                'master_group_id'=>$items[0]->master_group_id,
                'prod_type_id'=>$items[0]->prod_type_id,
                'prod_cat_id'=>$items[0]->prod_cat_id,
                'count' => $items->count(),
                'data' => $items->pluck('id'),
                'indent_numbers' => $items->pluck('indent_number')
            ];
        })->values()->all();
        $search = request('search', '');
        $perPage = request('perPage', 10);
        $data= ItemMasterModel::leftJoin('cs_company_store_location','cs_company_store_location.id','to_store_id')
            ->select('trns00a_indent_master.*','cs_company_store_location.sl_name')
            ->where('trns00a_indent_master.product_req',1)
            ->where('indent_number', 'like', "%{$search}%")
            ->where('pro_req_close',0)
            ->orderBy('id','DESC')
            ->paginate($perPage);
            return Helper::sendJson(
                [
                    'data'=>$data,
                    'catagory'=>$grouped
                ]
            );
    }

    //init data
    public function  initializeData(){
        $catagory=ProductCatagory::all();
        $store=CsCompanyStoreLocation::all();
        return response()->json([
            'catagory' => $catagory,
            'store' => $store,
        ]);
    }
    public function  closeProductRequisition($id){
        return ItemMasterModel::where('id',$id)->update(['pro_req_close'=>1]);
    }
    

    // indenet for requisition 
    public function indentforrequsition (){
        $search = request('search', '');
        $perPage = request('perPage', 10);
        $grp_master = request('grp_master', '');
            return ItemMasterModel::leftJoin('cs_company_store_location','cs_company_store_location.id','to_store_id')
            ->select(DB::raw("DATE_FORMAT(trns00a_indent_master.indent_date, '%d-%m-%Y') as indent_date_formate"),'trns00a_indent_master.*','cs_company_store_location.sl_name')
            ->where('product_req',0)
            ->where('close_status',0)
            ->where('pro_req_close',0)
            ->where('master_group_id',$grp_master)
            ->where('indent_number', 'like', "%{$search}%")
            ->orderBy('id','DESC')
            ->get();
    }
    //


    public function getAllIndentMergeValueForRequisition(Request $request){
        $indentNumbers=ItemMasterModel::select('id','indent_number') ->whereIn('id',$request->indentNumber)
        ->get();
        $identChild=ItemChildModel::leftJoin('var_item_info','var_item_info.id','trns00b_indent_child.item_info_id')
            ->leftJoin('5m_sv_uom','var_item_info.uom_id','=','5m_sv_uom.id')
            ->select(
                'trns00b_indent_child.uom_id',
                'trns00b_indent_child.id as child_id',
                'trns00b_indent_child.item_info_id',
                'trns00b_indent_child.indent_master_id',
                'var_item_info.display_itm_name',
                'trns00b_indent_child.uom_short_code',
                'trns00b_indent_child.indent_quantity',
                'trns00b_indent_child.Remarks as remarks'
            )
            ->whereIn('indent_master_id',$request->indentNumber)
                ->get();
        $dat=collect($identChild);
        $data=collect($dat)->groupBy('item_info_id');
        $result=[];
        $i=0;
        foreach($data as $key=>$value){
            $dd=collect($value)->where('item_info_id',$key)->values();
            $req_quantity=collect($value)->sum('indent_quantity');
            $result[$i]['item_information_id']=$value[0]->item_info_id;
            $result[$i]['uom_id'] =$value[0]->uom_id;
            $result[$i]['display_itm_name']=$value[0]->display_itm_name;
            $result[$i]['uom_short_code']=$value[0]->uom_short_code;
            $result[$i]['indent_quantity']=$req_quantity;
            $result[$i]['req_quantity']=$req_quantity;
            $result[$i]['required_date']=date('d-m-Y');
            $result[$i]['remarks']=$value[0]->remarks;
            $result[$i]['model']=false;
            $result[$i]['req_list']=$dd;
            $i++;
        }
        return response()->json([
            'child'=>$result,
            'indentNumbers' =>$indentNumbers 
        ]); 

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
    public function store(Request $request){
         $indentNumber =Helper::codeGenerate('trns00a_indent_master');
        try{
            DB::beginTransaction();
            $data=[];
            $data['company_id']=Auth::user()->company_id;
            $data['indent_date']=date('Y-m-d',strtotime($request->indent_date));
            $data['branch_id']= Auth::user()->branch_id;
            $data['indent_number']= $indentNumber;
            $data['order_master_id']=0;
            $data['demand_store_id'] = Auth::user()->store_id;
            $data['product_req']=1;
            $data['prod_type_id']= $request->pro_type;
            $data['prod_cat_id']= $request->pro_cat;
            $data['master_group_id']= $request->grp_master;
            $data['to_store_id']=$request->to_store_id;
            $data['created_by']=Auth::id();
            $data['submitted_by']=Auth::id();
            $data['recommended_by']=Auth::id();
            $data['approved_by']=Auth::id();
            $data['updated_by']= " ";
            $data['remarks']= $request->remarks;
           
            
            $indentMasterre=ItemMasterModel::create($data);
            foreach($request['item_row'] as $information){
                $information['indent_master_id']=$indentMasterre->id;
                $information['required_date']=date('Y-m-d',strtotime($information['required_date'])) ;
                // $information['indent_datete']=$indentMasterre->indent_date;
                $information['item_info_id']=$information['item_information_id'];
                $information['created_at']=Carbon::now();
                $information['updated_at']=Carbon::now();
                $information['created_by']=Auth::id();
                $information['updated_by']=" ";
                $this->createIndentChild($information);
            }
            ItemMasterModel::whereIn('id',$request->indentNumber)->update(['pro_req_close'=>1]);
            DB::commit();
            return $indentMasterre;
        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
            return response([
                'message' => $exp->getMessage(),
                'status' => 'failed'
            ], 400);
        }
    }
    public function createIndentChild($data){
        $validator = Validator::make($data, [
            'indent_master_id' => 'required',
            'item_info_id' => 'required',
            'uom_id'  => 'required',
            'uom_short_code'  => 'required',
            'relative_factor'  => 'nullable',
            'indent_quantity' => 'required',
            'consum_order_qty' => 'nullable',
            'Remarks' => 'nullable',
            'required_date' => 'required',
            'created_at'  => 'required',
            'updated_at'  => 'required',
            'created_by'  => 'nullable',
            'updated_by' => 'nullable'
        ]);
        $child=ItemChildModel::create($validator->validated());
        foreach($data['req_list'] as $item){
            IndentChildren::create([
                'indent_master_id' => $item['indent_master_id'],
                'indent_child_id' => $child->id,
                'Item_info_id' => $item['item_info_id'],
                'uom_id' => $item['uom_id'],
                'req_qty' => $item['indent_quantity'],
                'indent_qty' =>$item['indent_quantity'],
                'created_by'=> Auth::user()->id
            ]);
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
        //
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