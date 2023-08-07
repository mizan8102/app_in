<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\SubGroup;
use App\Models\ProductGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductSubGroupController extends Controller
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
        $productSubGroup = SubGroup::query()
            ->when($search, function ($query, $search) {
                return $query->where('itm_sub_grp_des', 'like', '%' . $search . '%')
                    ->orWhere('itm_sub_grp_des_bn', 'like', '%' . $search . '%')
                    ->orWhere('itm_sub_grp_prefix', 'like', '%' . $search . '%')
                    ->orWhere('sequence', 'like', '%' . $search . '%');
            })
            ->orderBy('itm_sub_grp_des', 'ASC')
            ->paginate($perPage);
        return sendJson('list of the product sub Group', $productSubGroup, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $search = request()->input('search');
        $productGroup = ProductGroup::query()
            ->when($search, function ($query, $search) {
                return $query->where('itm_grp_name', 'like', '%' . $search . '%')
                    ->orWhere('itm_grp_prefix', 'like', '%' . $search . '%')
                    ->orWhere('item_grp_des', 'like', '%' . $search . '%')
                    ->orWhere('item_grp_des_bn', 'like', '%' . $search . '%');
            })
            ->orderBy('itm_grp_name', 'ASC')
            ->get();
        return sendJson('list of the product Group', $productGroup, 200);
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
            'itm_grp_id' => 'required|numeric|exists:var_item_group,id',
            'inv_method_id' => 'sometimes|numeric|exists:5s_sv_inventory_method,id',
            'itm_sub_grp_des' => 'required|max:50',
            'itm_sub_grp_des_bn' => 'required|max:60',
            'itm_sub_grp_prefix' => 'required|max:20',
            'sequence' => 'required|numeric|regex:/^\d\.\d$/',
            'is_active' => 'required|boolean',
        ]);
        try {
            DB::beginTransaction();
            $productSubGroup = SubGroup::create([
                'itm_grp_id' => $request->itm_grp_id,
                'inv_method_id' => $request->inv_method_id,
                'itm_sub_grp_des' => $request->itm_sub_grp_des,
                'itm_sub_grp_des_bn' => $request->itm_sub_grp_des_bn,
                'itm_sub_grp_prefix' => $request->itm_sub_grp_prefix,
                'sequence' => $request->sequence,
                'is_active' => $request->is_active,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
            DB::commit();
            return sendJson('Sub Group Create Success', $productSubGroup, 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return sendJson('Sub Group Create failed', $th->getMessage(), 200);
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
        $productSubGroup = SubGroup::find($id);
        if ($productSubGroup) {
            return sendJson('product sub group found', $productSubGroup, 200);
        } else {
            return sendJson('product sub group not found', null, 400);
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
        $productSubGroup = SubGroup::find($id);
        if ($productSubGroup) {
            return sendJson('product sub group found', $productSubGroup, 200);
        } else {
            return sendJson('product sub group not found', null, 400);
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
        $productSubGroup = SubGroup::find($id);
        if ($productSubGroup) {
            return sendJson('product sub group found', $productSubGroup, 200);
        } else {
            return sendJson('product sub group not found', null, 400);
        }
        $validated = Validator::make($request->all(), [
            'itm_grp_id' => 'required|numeric|exists:var_item_group,id',
            'inv_method_id' => 'sometimes|numeric|exists:5s_sv_inventory_method,id',
            'itm_sub_grp_des' => 'required|max:50',
            'itm_sub_grp_des_bn' => 'required|max:60',
            'itm_sub_grp_prefix' => 'required|max:20',
            'sequence' => 'required|numeric|regex:/^\d\.\d$/',
            'is_active' => 'required|numeric',
        ]);
        if ($validated->fails()) {
            return sendJson('validation fails', $validated->errors(), 422);
        }
        try {
            DB::beginTransaction();
            $productSubGroup->update([
                'itm_grp_id' => $request->itm_grp_id,
                'inv_method_id' => $request->inv_method_id,
                'itm_sub_grp_des' => $request->itm_sub_grp_des,
                'itm_sub_grp_des_bn' => $request->itm_sub_grp_des_bn,
                'itm_sub_grp_prefix' => $request->itm_sub_grp_prefix,
                'sequence' => $request->sequence,
                'is_active' => $request->is_active,
                'updated_by' => auth()->user()->id,
            ]);
            DB::commit();
            return sendJson('Sub Group update failed', $productSubGroup, 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return sendJson('Sub Group update failed', $th->getMessage(), 200);
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
        $productSubGroup = SubGroup::find($id);
        if ($productSubGroup) {
            try {
                $productSubGroup->delete();
                return sendJson('product sub group deleted', null, 200);
            } catch (\Throwable $th) {
                return sendJson('product sub group delete failed', $th->getMessage(), 400);
            }
        } else {
            return sendJson('product sub group not found', null, 400);
        }
    }
}
