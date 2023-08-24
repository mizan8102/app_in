<?php

namespace App\Http\Controllers\ProgramIndent;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ItemMasterModel;
use App\Models\OrderMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProgramIndentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        foreach($request->master_group_wise_data as $key=>$msG){
            
        }
        $program_Data['pro_type'] = $request['master_group_wise_data'][0];

        return $this->indentSave($program_Data);
    }

    protected function indentSave($request){
        $comID = Auth::user()->company_id;
        $dataa = DB::select('CALL getTableID("trns00a_indent_master","'.$comID.'")');
        $indentNumber =$dataa[0]->masterID;
        
        try{
            DB::beginTransaction();
            $data['company_id']     = Auth::user()->company_id;
            $data['indent_date']    = date('Y-m-d',strtotime($request->indent_date));
            $data['branch_id']      = Auth::user()->branch_id;
            $data['indent_number']  = $indentNumber;
            $data['prod_type_id']   = $request->pro_type;
            $data['prod_cat_id']    = $request->pro_cat;
            $data['master_group_id']= $request->grp_master;
            $data['program_master_id']=0;
            $data['product_req']    =0;
            $data['demand_store_id']= Auth::user()->store_id;
            $data['created_by']     =Auth::id();
            $data['submitted_by']   =Auth::id();
            $data['recommended_by'] =Auth::id();
            $data['approved_by']    =Auth::id();
            $data['updated_by']     = "";

            $indentMasterre=ItemMasterModel::create($data);

            // foreach($request->item_row as $information){
            //     $information['indent_master_id']=$indentMasterre->id;
            //     $information['required_date']=date('Y-m-d',strtotime($information['required_data'])) ;
            //     // $information['indent_datete']=$indentMasterre->indent_date;
            //     $information['item_info_id']=$information['itm'];
            //     $information['created_at']=Carbon::now();
            //     $information['updated_at']=Carbon::now();
            //     $information['created_by']=Auth::id();
            //     $information['updated_by']=" ";
            //     $this->createIndentChild($information);
            // }
            DB::commit();
            return $indentNumber;
        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
            return response([
                'message' => $exp->getMessage(),
                'status' => 'failed'
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
