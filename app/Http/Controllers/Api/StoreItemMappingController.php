<?php

namespace App\Http\Controllers\Api;

use App\Models\VarItemInfo;
use Illuminate\Http\Request;
use App\Models\CsCompanyBranch;
use App\Models\ItemStoreMapping;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CsCompanyStoreLocation;
use Illuminate\Support\Facades\Validator;

class StoreItemMappingController extends Controller
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
        $data = ItemStoreMapping::
        leftJoin('var_item_info', 'var_item_mapping_bin_prodtype.item_information_id', '=', 'var_item_info.id')
            ->leftJoin('5f_sv_product_type', 'var_item_mapping_bin_prodtype.prod_type_id', '=', '5f_sv_product_type.id')
            ->leftJoin('cs_company_store_location', 'var_item_mapping_bin_prodtype.store_id', '=', 'cs_company_store_location.id')
            ->leftJoin('cs_company_branch_unit', 'var_item_mapping_bin_prodtype.branch_id', '=', 'cs_company_branch_unit.id')
            ->leftJoin('var_item_sub_group','var_item_sub_group.id','var_item_info.itm_sub_grp_id')
            ->select('var_item_mapping_bin_prodtype.id', 'var_item_mapping_bin_prodtype.item_information_id', 'var_item_mapping_bin_prodtype.prod_type_id', 'var_item_mapping_bin_prodtype.branch_id'
            , 'var_item_info.id AS item_information_id'
            ,
            '5f_sv_product_type.prod_type_name as prod_type_name'
            ,
            '5f_sv_product_type.prod_type_name_bn as prod_type_name_bn'
            ,
            'var_item_info.display_itm_name as display_item_name'
            ,
            'var_item_info.display_itm_name_bn as display_item_name_bn'
            ,
            'var_item_info.hs_code_id as hs_code_id'
            ,
            'cs_company_store_location.id as store_id'
            ,
            'cs_company_store_location.sl_name as sl_name'
            ,
            'cs_company_store_location.sl_name_bn as sl_name_bn'
            ,
            'cs_company_branch_unit.id as branch_id'
            ,
            'cs_company_branch_unit.b_u_name as b_u_name'
            ,
            'cs_company_branch_unit.b_u_name_bn as b_u_name_bn',
            'var_item_mapping_bin_prodtype.is_active as is_active',
            'var_item_sub_group.itm_sub_grp_des as itm_sub_grp_des'
            )
            ->paginate($perPage);

        return sendJson('Item Store mapping list', $data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            'items' => VarItemInfo::where('is_active', 1)->select('id', 'display_itm_name', 'display_itm_name_bn')->get(),
            'stores' => CsCompanyStoreLocation::where('is_active', 1)->select('id', 'sl_name', 'sl_name_bn')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->item_information_id);
        $request->validate([
            'item_information_id' => 'required|exists:var_item_info,id',
            'store_id' => 'required|exists:cs_company_store_location,id',
            'is_active' => 'required|boolean'
        ]);
        try {
            DB::beginTransaction();
            $mapped = ItemStoreMapping::create([
                'item_information_id' => $request->item_information_id,
                'prod_type_id' => VarItemInfo::find($request->item_information_id)->value('prod_type_id'),
                'store_id' => $request->store_id,
                'is_active' => $request->is_active,
            ]);
            DB::commit();
            return sendJson('item mapped to store', $mapped, 200);
        } catch (\Exception $th) {
            DB::rollBack();
            return sendJson('item mapped to store failed', $th->getMessage(), 500);
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
        $mapped = ItemStoreMapping::find($id);
        return sendJson('Mapped data', $mapped, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mapped = ItemStoreMapping::find($id);
        return sendJson('Mapped data', $mapped, 200);
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
        $mapped = ItemStoreMapping::find($id);
        $request->validate([
            'item_information_id' => 'required|exists:var_item_info,id',
            'store_id' => 'required|exists:cs_company_store_location,id',
            'is_active' => 'required|boolean'
        ]);
        if ($validated->fails()) {
            return sendJson('validation fails', $validated->errors(), 422);
        }
        try {
            DB::beginTransaction();
            $mapped->update([
                'item_information_id' => $request->item_information_id,
                'prod_type_id' => VarItemInfo::find($request->item_information_id)->value('prod_type_id'),
                'store_id' => $request->store_id,
                'is_active' => $request->is_active,
            ]);
            DB::commit();
            return sendJson('item mapped to store updated', $mapped, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendJson('item mapped to store failed', $th->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $mapped = ItemStoreMapping::find($id)->delete();
        } catch (\Throwable $th) {
            return sendJson(' failed', $th->getMessage(), 500);
        }
        return sendJson('Mapped data delete', $mapped, 200);
    }
    public function itemForStoreMappingByMastergroupSP(){
        $selectedMasterGroupId = request()->input('itm_mstr_grp_id');
        $selectedStoreId = request()->input('store_id');
        return DB::select('CALL GetUnMappedData("' . $selectedMasterGroupId . '","' . $selectedStoreId . '")');
    }
    public function GetMappedData(){
        $selectedMasterGroupId = request()->input('itm_mstr_grp_id');
        $selectedStoreId = request()->input('store_id');
        return DB::select('CALL GetMappedData("' . $selectedMasterGroupId . '","' . $selectedStoreId . '")');
    }
    public function indexNew(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10);
        $store = $request->input('store_id', '');
        $category = $request->input('category', '');
        $type = $request->input('type', '');
        $masterGroup = $request->input('master_group', '');


        $query = ItemStoreMapping::leftJoin('var_item_info', 'var_item_info.id', '=', 'var_item_mapping_bin_prodtype.item_information_id')
            ->leftJoin('cs_company_store_location', 'cs_company_store_location.id', '=', 'var_item_mapping_bin_prodtype.store_id')
            ->leftJoin('var_item_sub_group', 'var_item_sub_group.id', '=', 'var_item_info.itm_sub_grp_id')
            ->leftJoin('var_item_group', 'var_item_group.id', '=', 'var_item_sub_group.itm_grp_id')
            ->leftJoin('var_item_master_group', 'var_item_master_group.id', '=', 'var_item_group.master_group_id')
            ->leftJoin('5f_sv_product_type', '5f_sv_product_type.id', '=', 'var_item_master_group.prod_type_id')
            ->leftJoin('5h_sv_product_category', '5h_sv_product_category.id', '=', '5f_sv_product_type.prod_cat_id')
            ->select(
                'var_item_info.*',
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
            )
            ->where('var_item_mapping_bin_prodtype.store_id', $store);

        if ($category !== '') {
            $query->where('5h_sv_product_category.id', $category);
        }
        if ($type !== '') {
            $query->where('5f_sv_product_type.id', $type);
        }
        if ($masterGroup !== '') {
            $query->where('var_item_master_group.id', $masterGroup);
        }

        $results = $query->paginate($perPage);

        return $results;
    }

}
