<?php

namespace App\Http\Controllers\HouseKeeping;

use App\Http\Controllers\Controller;
use App\Models\HouseKeeping\VarItemMappingBinProdtype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StoreWiseItemController extends Controller
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
    public function init_store_mappingGetMasterId($id){
        return VarItemMappingBinProdtype::select('var_item_master_group.id','var_item_master_group.itm_mstr_grp_name')
       ->leftJoin('var_item_info','var_item_info.id','var_item_mapping_bin_prodtype.item_info_id')
       ->leftJoin('var_item_sub_group','var_item_sub_group.id','=','var_item_info.itm_sub_grp_id')
       ->leftJoin('var_item_group', 'var_item_group.id', 'var_item_sub_group.itm_grp_id')
       ->leftJoin('var_item_master_group', 'var_item_group.itm_mstr_grp_id', 'var_item_master_group.id')
       ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','var_item_master_group.prod_type_id')
       ->where('var_item_master_group.prod_type_id',$id)
        ->where('var_item_mapping_bin_prodtype.is_active',1)->groupBy('var_item_master_group.id')
        ->get();
    }

    public function init_store_product_type($id){
        return VarItemMappingBinProdtype::select('5f_sv_product_type.id','5f_sv_product_type.prod_type_name as no')
        ->leftJoin('var_item_info','var_item_info.id','var_item_mapping_bin_prodtype.item_info_id')
        ->leftJoin('var_item_sub_group','var_item_sub_group.id','=','var_item_info.itm_sub_grp_id')
        ->leftJoin('var_item_group', 'var_item_group.id', 'var_item_sub_group.itm_grp_id')
        ->leftJoin('var_item_master_group', 'var_item_group.itm_mstr_grp_id', 'var_item_master_group.id')
        ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','var_item_master_group.prod_type_id')
        ->where('var_item_mapping_bin_prodtype.store_id',$id)
         ->where('var_item_mapping_bin_prodtype.is_active',1)
         ->groupBy('var_item_master_group.prod_type_id')
         ->get();
    }

    // param with value
    public function  storeWiseItemWithParam(Request $request)
    {
        $search = request('search', '');
        $limit = request('limit', 10);
        $catagory = request('catagory', '');
        $type = request('type', '');
        $master_group = request('master_group', '');
        $group = request('group', '');
        $sub_group = request('sub_group', '');
        $store_id = request('store_id', '');
        $data= VarItemMappingBinProdtype::query()
        ->select('cs_company_store_location.sl_name', 'var_item_mapping_bin_prodtype.id', 'var_item_info.display_itm_name',
            'var_item_info.display_itm_name_bn', 'var_item_sub_group.itm_sub_grp_des', 'var_item_group.itm_grp_name', 'var_item_group.id as group_id',
            'var_item_master_group.id as master_group_id', 'var_item_master_group.itm_mstr_grp_name','5h_sv_product_category.id as cat_id','5h_sv_product_category.prod_cat_name',
            '5f_sv_product_type.prod_type_name')
            ->leftJoin('var_item_info', 'var_item_info.id', 'var_item_mapping_bin_prodtype.item_info_id')
            ->leftJoin('cs_company_store_location', 'cs_company_store_location.id', 'var_item_mapping_bin_prodtype.store_id')
            ->leftJoin('var_item_sub_group', 'var_item_sub_group.id', 'var_item_info.itm_sub_grp_id')
            ->leftJoin('var_item_group', 'var_item_group.id', 'var_item_sub_group.itm_grp_id')
            ->leftJoin('var_item_master_group', 'var_item_group.itm_mstr_grp_id', 'var_item_master_group.id')
            ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','var_item_master_group.prod_type_id')
            ->leftJoin('5h_sv_product_category','5h_sv_product_category.id','5f_sv_product_type.prod_cat_id')
            ->where('var_item_mapping_bin_prodtype.is_active',1);
            if($store_id){
                $data->where('var_item_mapping_bin_prodtype.store_id', $store_id );
            }
            if($catagory){
                $data->where('5h_sv_product_category.id', $catagory);
            }
            if($type){
                $data->where('5f_sv_product_type.id',$type);
            }
            if($master_group){
                $data ->where('var_item_master_group.id',$master_group);
            }
           return $data->where('var_item_info.display_itm_name', 'like', '%' . $search . '%')->paginate($limit);   
    }
    public function item_store_wise(){
     $store_id=\request('store_id');
     $master_group=\request('master_id');
     return DB::select('CALL GetItemsByStoreAndMaster("'.$store_id.'","' .$master_group.'"," ")');
    }

    // bar item come data
    public  function barCodeComeItemStoreWise(){
        $master_id=\request('masterGroup');
        $store_id=\request('store_id');
        $item_code=\request('item_code');
        return DB::select('CALL GetItemsByStoreAndMaster("'.$store_id.'","' .$master_id.'","' .$item_code.'")');
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
        $validated = Validator::make($request->all(), [
            'pro_type' => 'required',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validated->errors()
            ], 422);
        }
        $branch=Auth::user()->branch_id;
        $created_by=Auth::user()->id;
        try{
            foreach ($request['item_row'] as $item){
                VarItemMappingBinProdtype::create([
                    'item_info_id' => $item['id'],
                    'prod_type_id'=> $request->pro_type,
                    'branch_id'=>$branch,
                    'is_active'=>1,
                    'status'=>1,
                    'created_by'=>$created_by,
                    'store_id'=>$request->to_store_id
                ]);
            }
            return "Successfully added";
        }
            catch (\Exception $ex){
            return $ex;
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
        $modal_data=VarItemMappingBinProdtype::find($id);
        if($modal_data){
            $modal_data->delete();
        }
        return "Deleted successfull";
    }
}
