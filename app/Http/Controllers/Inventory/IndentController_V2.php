<?php

namespace App\Http\Controllers\Inventory;


use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryIndentRequest;
use App\Models\CsCompanyStoreLocation;
use App\Models\ItemChildModel;
use App\Models\ItemMasterGroup;
use App\Models\ItemMasterModel;
use App\Models\ProductCatagory;
use App\Models\ProductGroup;
use App\Models\ProductType;
use App\Models\SubGroup;
use App\Models\VarItemInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IndentController extends Controller
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
            return ItemMasterModel::leftJoin('cs_company_store_location','cs_company_store_location.id','to_store_id')
            ->select('trns00a_indent_master.*','cs_company_store_location.sl_name')
            ->where('product_req',0)
            ->where('indent_number', 'like', "%{$search}%")
            ->orderBy('id','DESC')
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



    // initialize data
    public  function  initializeData(){
        $pro_cat= ProductCatagory::select('id','prod_cat_name')->get();
        $store=CsCompanyStoreLocation::all();
        return response()->json([
            'pro_cat' => $pro_cat,
            'store' => $store,
        ]);
    }

    public function initprotype($id){
        return ProductType::select('id','prod_type_name')->where('prod_cat_id',$id)->get();
    }

    public function initProgGrpMaster($id){
        return ItemMasterGroup::select('id','itm_mstr_grp_name')->where('prod_type_id',$id)->get();
    }
    public  function initProdVarGroup($id){
        return ProductGroup::select('id','itm_grp_name')->where('itm_mstr_grp_id',$id)->get();
    }

    public function initprosubGrp($id){
        return SubGroup::select('id','itm_sub_grp_des')->where('itm_grp_id',$id)->get();
    }

    // indent for sub item
    public function initItemforinventoryIndent($id){
        return VarItemInfo::select('var_item_info.id','var_item_info.display_itm_name','var_item_info.uom_id','closing_bal_amount')
        ->leftJoin('trns_itemstock_master','trns_itemstock_master.item_information_id','var_item_info.id')
        ->leftJoin('trns_itemstock_child','trns_itemstock_child.itemstock_master_id','trns_itemstock_master.id')
     
        ->with('sv_uom')->where('itm_sub_grp_id',$id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InventoryIndentRequest $request)
    {
        $comID = Auth::user()->company_id;
        $dataa = DB::select('CALL getTableID("trns00a_indent_master","'.$comID.'")');
        $indentNumber =$dataa[0]->masterID;
        
        try{
            DB::beginTransaction();
            $data=$request->validated();
            $data['company_id']=Auth::user()->company_id;
            $data['indent_date']=date('Y-m-d',strtotime($data['indent_date']));
            $data['branch_id']= Auth::user()->branch_id;
            $data['indent_number']= $indentNumber;
            $data['prod_type_id']= $data['pro_type'];
            $data['prod_cat_id']= $data['pro_cat'];
            $data['master_group_id']= $data['grp_master'];
            $data['program_master_id']=0;
            $data['product_req']=0;
            $data['demand_store_id'] = Auth::user()->store_id;
            $data['created_by']=Auth::id();
            $data['submitted_by']=Auth::id();
            $data['recommended_by']=Auth::id();
            $data['approved_by']=Auth::id();
            $data['updated_by']= " ";
           
            $indentMasterre=ItemMasterModel::create($data);
            
            foreach($data['item_row'] as $information){
                $information['indent_master_id']=$indentMasterre->id;
                $information['required_date']=date('Y-m-d',strtotime($information['required_data'])) ;
                // $information['indent_datete']=$indentMasterre->indent_date;
                $information['item_info_id']=$information['itm'];
                $information['created_at']=Carbon::now();
                $information['updated_at']=Carbon::now();
                $information['created_by']=Auth::id();
                $information['updated_by']=" ";
                $this->createIndentChild($information);
            }
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
        return ItemMasterModel::with(['item_indent_child'=>function($query){
            $query->leftJoin('var_item_info','var_item_info.id','trns00b_indent_child.item_information_id')
                ->leftJoin('5m_sv_uom','var_item_info.uom_id','=','5m_sv_uom.id')
                ->get();
        }])->leftJoin('cs_company_store_location as StoreTo','StoreTo.id','to_store_id')
            ->leftJoin('cs_company_store_location as mainStore','mainStore.id','demand_store_id')
            ->leftJoin('users','users.id','submitted_by')
            ->select('trns00a_indent_master.*','StoreTo.sl_name','mainStore.sl_name as mainstore','users.name')
            ->find($id);
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
