<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductType;
use Illuminate\Http\Request;
use App\Models\ItemMasterGroup;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductMasterGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request()->input('search');
        $perPage = request()->input('perPage', 10);
        $productMasterGroup = ItemMasterGroup::query()
            ->when($search, function ($query, $search) {
                return $query->where('itm_mstr_grp_name', 'like', '%' . $search . '%')
                    ->orWhere('itm_mstr_grp_prefix', 'like', '%' . $search . '%')
                    ->orWhere('item_mstr_grp_des_bn', 'like', '%' . $search . '%')
                    ->orWhere('item_mstr_grp_des', 'like', '%' . $search . '%');
            })
            ->orderBy('itm_mstr_grp_name', 'ASC')
            ->paginate($perPage);
        return sendJson('list of the product master Group', $productMasterGroup, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $search = request()->input('search');
        $productType = ProductType::query()
            ->when($search, function ($query, $search) {
                return $query->where('prod_type_name', 'like', '%' . $search . '%')
                    ->orWhere('prod_type_name_bn', 'like', '%' . $search . '%');
            })
            ->orderBy('prod_type_name', 'ASC')
            ->get();
        return sendJson('list of the product type', $productType, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // ,except,id
        $request->validate([
            'prod_type_id' => 'required|numeric|exists:5f_sv_product_type,id',
            'itm_mstr_grp_name' => 'required|max:100|unique:var_item_master_group,itm_mstr_grp_name',
            'itm_mstr_grp_prefix' => 'required|max:30|unique:var_item_master_group,itm_mstr_grp_prefix',
            'item_mstr_grp_des' => 'required|max:100',
            'item_mstr_grp_des_bn' => 'required|max:100',
            'sequence' => 'sometimes|numeric|regex:/^\d\.\d$/',
            'is_active' => 'required|numeric',
        ]);
        try {
            DB::beginTransaction();
            $productMasterGroup = ItemMasterGroup::create([
                'prod_type_id' => $request->prod_type_id,
                'itm_mstr_grp_name' => $request->itm_mstr_grp_name,
                'itm_mstr_grp_prefix' => $request->itm_mstr_grp_prefix,
                'item_mstr_grp_des' => $request->item_mstr_grp_des,
                'item_mstr_grp_des_bn' => $request->item_mstr_grp_des_bn,
                'sequence' => $request->sequence,
                'is_active' => $request->is_active,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
            DB::commit();
            return sendJson('Product Master Group has Been created', $productMasterGroup, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendJson('Product Master Group create failed', $th->getMessage(), 400);
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
        $productMasterGroup = ItemMasterGroup::find($id);
        if ($productMasterGroup) {
            return sendJson('Product master group found', $productMasterGroup, 200);
        } else {
            return sendJson('Product master group not found', null, 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $productMasterGroup = ItemMasterGroup::find($id);
        if ($productMasterGroup) {
            return sendJson('Product master group found', $productMasterGroup, 200);
        } else {
            return sendJson('Product master group not found', null, 200);
        }
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
        $productMasterGroup = ItemMasterGroup::find($id);
        if ($productMasterGroup) {
            $validated = Validator::make($request->all(), [
                'prod_type_id' => 'required|numeric|exists:5f_sv_product_type,id',
                'itm_mstr_grp_name' => 'required|max:100|unique:itm_mstr_grp_name,itm_mstr_grp_name,except,id',
                'itm_mstr_grp_prefix' => 'required|max:30|unique:itm_mstr_grp_name,itm_mstr_grp_prefix,except,id',
                'item_mstr_grp_des' => 'required|max:100',
                'item_mstr_grp_des_bn' => 'required|max:100',
                'sequence' => 'required|numeric|regex:/^\d\.\d$/',
                'is_active' => 'required|numeric',
            ]);
            if ($validated->fails()) {
                return sendJson('validation fails', $validated->errors(), 422);
            }
            try {
                DB::beginTransaction();
                $productMasterGroup->update([
                    'prod_type_id' => $request->prod_type_id,
                    'itm_mstr_grp_name' => $request->itm_mstr_grp_name,
                    'itm_mstr_grp_prefix' => $request->itm_mstr_grp_prefix,
                    'item_mstr_grp_des' => $request->item_mstr_grp_des,
                    'item_mstr_grp_des_bn' => $request->item_mstr_grp_des_bn,
                    'sequence' => $request->sequence,
                    'is_active' => $request->is_active,
                    'updated_by' => auth()->user()->id,
                ]);
                DB::commit();
                return sendJson('Product Master Group has Been updated', $productMasterGroup, 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                return sendJson('Product Master Group updated failed', $productMasterGroup, 400);
            }
        } else {
            return sendJson('Product master group not found', null, 200);
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
        $productMasterGroup = ItemMasterGroup::find($id);
        if ($productMasterGroup) {
            try {
                $productMasterGroup->delete();
                return sendJson('Product master group deleted', null, 200);
            } catch (\Throwable $th) {
                return sendJson('Product master group delete failed', $productMasterGroup, 400);
            }
        } else {
            return sendJson('Product master group not found', null, 400);
        }
    }
}