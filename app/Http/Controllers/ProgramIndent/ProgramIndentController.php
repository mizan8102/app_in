<?php

namespace App\Http\Controllers\ProgramIndent;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ItemChildModel;
use App\Models\ItemMasterModel;
use App\Models\OrderMaster;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProgramIndentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search', '');
        $perPage = request('perPage', 5);
        $pendingData=OrderMaster::select(
            'trns00g_order_master.*',
            'cs_company_store_location.sl_name as floor_name',
            'cs_customer_details.customer_name',
            'var_program_sessions.session_name',
            'var_program_sessions.start_time',
            'var_program_sessions.end_time',
            '5x3_order_status.type_name'
        )
        ->leftJoin('5x3_order_status','5x3_order_status.id','trns00g_order_master.order_type_id')
        ->leftJoin('var_program_sessions', 'trns00g_order_master.program_session_id', '=', 'var_program_sessions.id')
        ->join('cs_company_store_location', 'cs_company_store_location.id', '=', 'trns00g_order_master.floor_id')
        ->join('cs_customer_details', 'trns00g_order_master.customer_id', '=', 'cs_customer_details.id')
        ->where('trns00g_order_master.status',0)
        // ->where('trns00g_order_master.program_name','like', "%{$search}%")
        ->orderBy('trns00g_order_master.id','DESC')->get();
        // ->paginate($perPage);

        // 
        $data=OrderMaster::select(
            'trns00g_order_master.*',
            'cs_company_store_location.sl_name as floor_name',
            'cs_customer_details.customer_name',
            'var_program_sessions.session_name',
            'var_program_sessions.start_time',
            'var_program_sessions.end_time',
            '5x3_order_status.type_name'
        )
        ->leftJoin('5x3_order_status','5x3_order_status.id','trns00g_order_master.order_type_id')
        ->leftJoin('var_program_sessions', 'trns00g_order_master.program_session_id', '=', 'var_program_sessions.id')
        ->join('cs_company_store_location', 'cs_company_store_location.id', '=', 'trns00g_order_master.floor_id')
        ->join('cs_customer_details', 'trns00g_order_master.customer_id', '=', 'cs_customer_details.id')
        ->where('trns00g_order_master.status',1)
        ->where('trns00g_order_master.program_name','like', "%{$search}%")
        ->orderBy('trns00g_order_master.id','DESC')
        ->paginate($perPage);
        
        return sendResponse([
            "data"          => $data,
            "pendingData"   => $pendingData
        ],200);
    }

    public function programInit($id) {
        $data = DB::select("CALL `GetConsumtionDetailByOrderMaster`('".$id."')");
        $masterGroupWiseData=collect($data)->groupBy('masterGroupId')->values();
        // parent Data with child 
        $orderMaster = OrderMaster::with(['orderChild'=>function($query){
            $query->with('item_info.sv_uom')
            ->leftJoin('var_item_info','var_item_info.id','trns00h_order_child.item_info_id')
            ->leftJoin('5m_sv_uom','5m_sv_uom.id','var_item_info.uom_id')
            ->where('var_item_info.prod_type_id',3);
    },'hallRoom'])
    ->find($id);
        return Helper::sendJson([
            'masterGroup' => $masterGroupWiseData,
            'orderMaster' => $orderMaster
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
    public function store(Request $request)
    {  
        $program_id = $request->program_id;
        $indent_date = $request->indent_date;
        $aa=array();
        foreach($request->master_group_wise_data as $key=>$msG){
            $arr['pro_type']    = $msG[0]['productTypeId'];
            $arr['pro_cat']     = $msG[0]['prodCategoryId'];
            $arr['grp_master']  = $msG[0]['masterGroupId'];
            $arr['order_master_id'] = $program_id;
            $arr['indent_date'] = $indent_date;
            $arr['children']    = $msG;
            $aa[$key]= $this->indentSave($arr);
        }
        OrderMaster::where('id',$program_id)->update(['status' => 1]);
        return $aa;
    }

    protected function indentSave($request){
        $comID = Auth::user()->company_id;
        $dataa = DB::select('CALL getTableID("trns00a_indent_master","'.$comID.'")');
        $indentNumber =$dataa[0]->masterID;
        try{
            DB::beginTransaction();
            $data['company_id']     = Auth::user()->company_id;
            $data['indent_date']    = date('Y-m-d',strtotime($request['indent_date']));
            $data['branch_id']      = Auth::user()->branch_id;
            $data['indent_number']  = $indentNumber;
            $data['prod_type_id']   = $request['pro_type'];
            $data['order_master_id']= $request['order_master_id'];
            $data['prod_cat_id']    = $request['pro_cat'];
            $data['master_group_id']= $request['grp_master'];
            $data['program_master_id']=0;
            $data['product_req']    =0;
            $data['demand_store_id']= Auth::user()->store_id;
            $data['created_by']     =Auth::id();
            $data['submitted_by']   =Auth::id();
            $data['recommended_by'] =Auth::id();
            $data['approved_by']    =Auth::id();
            $data['updated_by']     = "";

            $indentMasterre=ItemMasterModel::create($data);
            foreach($request['children'] as $information){
                $information['indent_master_id']=$indentMasterre->id;
                $information['uom_short_code']=$information['conUOM'];
                $information['indent_quantity']=$information['totalConsumption'];
                $information['consum_order_qty']=$information['totalConsumption'];
                $information['relative_factor']=1;
                $information['required_date']=date('Y-m-d',strtotime($information['requiredDate'])) ;
                $information['created_at']=Carbon::now();
                $information['updated_at']=Carbon::now();
                $information['created_by']=Auth::id();
                $information['updated_by']=" ";
                $this->createIndentChild($information);
            }
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

        return ItemChildModel::create($validator->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        // parent Data with child 
        $orderMaster = OrderMaster::with(['orderChild'=>function($query){
            $query->with('item_info.sv_uom')
            ->leftJoin('var_item_info','var_item_info.id','trns00h_order_child.item_info_id')
            ->leftJoin('5m_sv_uom','5m_sv_uom.id','var_item_info.uom_id')
            ->where('var_item_info.prod_type_id',3);
    },'hallRoom'])
    ->find($id);


        $masterGroupWiseData=ItemMasterModel::leftJoin('trns00b_indent_child','trns00b_indent_child.indent_master_id','trns00a_indent_master.id')
        ->leftJoin('var_item_info','trns00b_indent_child.item_info_id','var_item_info.id')
        ->where('order_master_id',$id)
        ->get();

        $dd=collect($masterGroupWiseData)->groupBy('indent_number')->values();




        return Helper::sendJson([
            'masterGroup' => $dd,
            'orderMaster' => $orderMaster
        ]);
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
