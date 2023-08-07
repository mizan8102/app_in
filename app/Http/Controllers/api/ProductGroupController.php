<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductGroup;
use Illuminate\Http\Request;
use App\Models\ItemMasterGroup;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductGroupController extends Controller
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
        $productGroup = ProductGroup::query()
            ->leftJoin('var_item_master_group', 'var_item_master_group.id', 'var_item_group.itm_mstr_grp_id')
            ->when($search, function ($query, $search) {
                return $query->where('itm_grp_name', 'like', '%' . $search . '%')
                    ->orWhere('itm_grp_prefix', 'like', '%' . $search . '%')
                    ->orWhere('item_grp_des', 'like', '%' . $search . '%')
                    ->orWhere('item_grp_des_bn', 'like', '%' . $search . '%');
            })
            ->orderBy('itm_grp_name', 'ASC')
            ->select('var_item_group.*', 'var_item_master_group.itm_mstr_grp_name as mstr_grp_name')
            ->paginate($perPage);
        return sendJson('list of the product Group', $productGroup, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $search = request()->input('search');
        $productMasterGroup = ItemMasterGroup::query()
            ->when($search, function ($query, $search) {
                return $query->where('itm_mstr_grp_name', 'like', '%' . $search . '%')
                    ->orWhere('itm_mstr_grp_prefix', 'like', '%' . $search . '%')
                    ->orWhere('item_mstr_grp_des_bn', 'like', '%' . $search . '%')
                    ->orWhere('item_mstr_grp_des', 'like', '%' . $search . '%');
            })
            ->orderBy('itm_mstr_grp_name', 'ASC')
            ->get();
        return sendJson('list of the product master group', $productMasterGroup, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'itm_mstr_grp_id' => 'required|numeric|exists:var_item_master_group,id',
            'itm_grp_name' => 'required|max:100|unique:var_item_group,itm_grp_name',
            'itm_grp_prefix' => 'required|max:30|unique:var_item_group,itm_grp_prefix',
            'uom_set_id' => 'sometimes|numeric|exists:5l_sv_uom_set,id',
            'item_grp_des' => 'required|max:100',
            'item_grp_des_bn' => 'required|max:100',
            'is_active' => 'required|boolean',
        ]);
        try {
            DB::beginTransaction();
            $productGroup = ProductGroup::create([
                'itm_mstr_grp_id' => $request->itm_mstr_grp_id,
                'itm_grp_name' => $request->itm_grp_name,
                'itm_grp_prefix' => $request->itm_grp_prefix,
                'uom_set_id' => $request->uom_set_id != null ? $request->uom_set_id : 1,
                'item_grp_des' => $request->item_grp_des,
                'item_grp_des_bn' => $request->item_grp_des_bn,
                'is_active' => $request->is_active,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
            DB::commit();
            return sendJson('product group created', $productGroup, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendJson('product group create failed', $th->getMessage(), 400);
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
        $productGroup = ProductGroup::find($id);
        if ($productGroup) {
            return sendJson('product group found', $productGroup, 200);
        } else {
            return sendJson('product group not found', null, 400);
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
        $productGroup = ProductGroup::find($id);
        if ($productGroup) {
            return sendJson('product group found', $productGroup, 200);
        } else {
            return sendJson('product group not found', null, 400);
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
        $productGroup = ProductGroup::find($id);
        if ($productGroup) {
            return sendJson('product group found', $productGroup, 200);
        } else {
            return sendJson('product group not found', null, 400);
        }
        try {
            $validated = Validator::make($request->all(), [
                'itm_mstr_grp_id' => 'required|numeric|exists:var_item_master_group,id',
                'itm_grp_name' => 'required|max:100|unique:var_item_group,itm_grp_name',
                'itm_grp_prefix' => 'required|max:30|unique:var_item_group,itm_grp_prefix',
                'uom_set_id' => 'sometimes|numeric|exists:5l_sv_uom_set,id',
                'item_grp_des' => 'required|max:100',
                'item_grp_des_bn' => 'required|max:100',
                'sequence' => 'required|numeric|regex:/^\d\.\d$/',
                'is_active' => 'required|numeric',
            ]);
            if ($validated->fails()) {
                return sendJson('validation fails', $validated->errors(), 422);
            }
            DB::beginTransaction();
            $productGroup = ProductGroup::create([
                'itm_mstr_grp_id' => $request->itm_mstr_grp_id,
                'itm_grp_name' => $request->itm_grp_name,
                'itm_grp_prefix' => $request->itm_grp_prefix,
                'uom_set_id' => $request->uom_set_id != null ? $request->uom_set_id : null,
                'item_grp_des' => $request->item_grp_des,
                'item_grp_des_bn' => $request->item_grp_des_bn,
                'sequence' => $request->sequence,
                'is_active' => $request->is_active,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
            DB::commit();
            return sendJson('product group created', $productGroup, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendJson('product group create failed', $th->getMessage(), 400);
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
        $productGroup = ProductGroup::find($id);
        if ($productGroup) {
            try {
                $productGroup->delete();
                return sendJson('product group deleted', null, 200);
            } catch (\Throwable $th) {
                return sendJson('product group delete failed', $productGroup, 200);
            }
        } else {
            return sendJson('product group not found', null, 400);
        }
    }
}