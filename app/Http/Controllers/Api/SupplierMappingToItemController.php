<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\ProductType;
use App\Models\VarItemInfo;
use Illuminate\Http\Request;
use App\Models\SupplierDetail;
use App\Models\SupplierMapping;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SupplierMappingToItemController extends Controller
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
        $supplier = SupplierMapping::query()
                ->leftJoin('cs_supplier_details','cs_supplier_details.id','supplier_mappings.sup_id')
                ->leftJoin('var_item_info','var_item_info.id','supplier_mappings.item_id')
                ->select('supplier_mappings.*','cs_supplier_details.supplier_name as supplier_name','cs_supplier_details.supplier_name_bn as supplier_name_bn','var_item_info.display_itm_name as display_itm_name','var_item_info.display_itm_name_bn as display_item_name_bn')
            ->paginate($perPage);

        return sendJson('list of the supplier details', $supplier, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $supplier = SupplierDetail::where('is_active', 1)->select('supplier_name','id')->get();
        $items = VarItemInfo::where('prod_type_id', ProductType::where('prod_type_name', 'like', 'Raw Materials')->first()->id)->where('is_active', 1)->select('display_itm_name','id')->get();
        return response()->json([
            'supplier' => $supplier,
            'items' => $items,
        ], 200);
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
            'sup_id' => 'required|numeric|exists:cs_supplier_details,id',
            'item_id' => 'required|numeric|exists:var_item_info,id',
            'is_active' => 'sometimes|boolean',
        ]);
        try {
            DB::beginTransaction();
            $supplierMapping = SupplierMapping::create([
                'is_active' => $request->is_active,
                'sup_id' => $request->sup_id,
                'item_id' => $request->item_id,
                'created_at'=>now(),
            ]);
            DB::commit();
            return $supplierMapping;
        } catch (Throwable $th) {
            DB::rollBack();
            return sendJson('Supplier Failed to Map to Product', $th->getMessage(), 200);
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
        $supplierMapping = SupplierMapping::with('supplier_detail', 'itemInfo')->find($id);
        if ($supplierMapping) {
            return sendJson('Supplier mapping to product found', $supplierMapping, 200);
        } else {
            return sendJson('Supplier mapping to product not found', $supplierMapping, 400);
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
        $supplierMapping = SupplierMapping::with('supplier_detail', 'itemInfo')->find($id);
        if ($supplierMapping) {
            return sendJson('Supplier mapping to product found', $supplierMapping, 200);
        } else {
            return sendJson('Supplier mapping to product not found', $supplierMapping, 400);
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
        $supplierMapping = SupplierMapping::with('supplier_detail', 'itemInfo')->find($id);
        if ($supplierMapping) {
            $request->validate([
                'sup_id' => 'required|numeric|exists:table,column',
                'item_id' => 'required|numeric|exists:table,column',
                'is_active' => 'sometimes|boolean',
            ]);
            try {
                DB::beginTransaction();
                $supplierMapping->update([
                    'is_active' => $request->is_active,
                    'sup_id' => $request->sup_id,
                    'item_id' => $request->item_id,
                    'updated_at'=>now(),
                ]);
                DB::commit();
                return sendJson('Supplier mapping to product update success', $supplierMapping, 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                return sendJson('Supplier mapping to product update failed', $th->getMessage(), 200);
            }
        } else {
            return sendJson('Supplier mapping to product not found', $supplierMapping, 400);
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
        $supplierMapping = SupplierMapping::with('supplier_detail', 'itemInfo')->find($id);
        if ($supplierMapping) {
            try {
                DB::beginTransaction();
                $supplierMapping->delete();
                DB::commit();
                return sendJson('Supplier mapping to product delete success', $supplierMapping, 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                return sendJson('Supplier mapping to product delete failed', $th->getMessage(), 200);
            }
        } else {
            return sendJson('Supplier mapping to product not found', $supplierMapping, 400);
        }
    }
}
