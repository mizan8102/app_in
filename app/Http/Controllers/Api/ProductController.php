<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\SvUOM;
use App\Models\Currency;
use App\Models\SubGroup;
use App\Models\ProductType;
use App\Models\VarItemInfo;
use App\Models\ProductGroup;
use Illuminate\Http\Request;
use App\Models\ItemMasterGroup;
use App\Models\ProductCatagory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CsCompanyStoreLocation;
use App\Models\VarItemDetails;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
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
        $product = VarItemInfo::query()
            ->when($search, function ($query, $search) {
                return $query->where('itm_code', 'like', '%' . $search . '%')
                    ->orWhere('display_itm_name', 'like', '%' . $search . '%')
                    ->orWhere('mushak_itm_name', 'like', '%' . $search . '%');
            })
            ->orderBy('display_itm_name', 'ASC')
            ->paginate($perPage);
        return sendJson('list of the product', $product, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = request()->input('category');
        $type = request()->input('type');
        $masterGroup = request()->input('masterGroup');
        $group = request()->input('group');
        $subGroup = request()->input('subGroup');
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
            'itm_sub_grp_id' => 'required|numeric|exists:var_item_sub_group,id',
            'prod_type_id' => 'required|numeric|exists:5f_sv_product_type,id',
            'display_itm_name' => 'required|max:100',
            'display_itm_name_bn' => 'required|max:100',
            'uom_id' => 'required|numeric|exists:5m_sv_uom,id',
            'current_rate' => 'required|numeric',
            'safety_level' => 'required|numeric',
            'reorder_level' => 'required|numeric',
            'is_active' => 'required|min:0|max:1',
            'item_image' => 'required|image',
        ]);
        if ($validated->fails()) {
            return sendJson('validation fails', $validated->errors(), 422);
        }
        try {
            $comID = auth()->user()->company_id;
            $productNumber = DB::select('CALL getTableID("var_item_info","' . $comID . '")');
            $productNumber = $productNumber[0]->masterID;
            DB::beginTransaction();
            $product = VarItemInfo::create([
                "itm_sub_grp_id" => $request->itm_sub_grp_id,
                "prod_type_id" => $request->prod_type_id,
                "itm_code" => $productNumber,
                "display_itm_code" => $productNumber,
                "display_itm_name" => $request->display_itm_name,
                "display_itm_name_bn" => $request->display_itm_name_bn,
                "mushak_itm_name" => $request->display_itm_name,
                "mushak_itm_name_bn" => $request->display_itm_name_bn,
                "uom_id" => $request->uom_id,
                "current_rate" => $request->current_rate,
                "safety_level" => $request->safety_level,
                "reorder_level" => $request->reorder_level,
                "is_active" => $request->is_active,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
            VarItemDetails::create([
                'item_information_id' => $product->id,
                'description' => $product->description,
                'description_bn' => $product->description_bn,
                'item_image' => $request->item_image,
                'price' => $product->current_rate,
                'updated_by' => auth()->user()->id,
            ]);
            DB::commit();
            return sendJson('Item Create success', $product, 400);
        } catch (\Throwable $th) {
            DB::rollback();
            return sendJson('Item Create Failed', $th->getMessage(), 400);
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
        $product = VarItemInfo::find($id);
        if ($product) {
            return sendJson('Product Found', $product, 200);
        } else {
            return sendJson('Product Not Found', $product, 400);
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
        $product = VarItemInfo::find($id);
        if ($product) {
            return sendJson('Product Found', $product, 200);
        } else {
            return sendJson('Product Not Found', $product, 400);
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
        $product = VarItemInfo::find($id);
        if ($product) {
            $validated = Validator::make($request->all(), [
                'itm_sub_grp_id' => 'required|numeric|exists:var_item_sub_group,id',
                'prod_type_id' => 'required|numeric|exists:5f_sv_product_type,id',
                'display_itm_name' => 'required|max:100',
                'display_itm_name_bn' => 'required|max:100',
                'uom_id' => 'required|numeric|exists:5m_sv_uom,id',
                'current_rate' => 'required|numeric',
                'safety_level' => 'required|numeric',
                'reorder_level' => 'required|numeric',
                'is_active' => 'required|min:0|max:1',
                'item_image' => 'required|image',
            ]);
            if ($validated->fails()) {
                return sendJson('validation fails', $validated->errors(), 422);
            }
            try {
                DB::beginTransaction();
                $product->update([
                    "itm_sub_grp_id" => $request->itm_sub_grp_id,
                    "prod_type_id" => $request->prod_type_id,
                    "display_itm_name" => $request->display_itm_name,
                    "display_itm_name_bn" => $request->display_itm_name_bn,
                    "mushak_itm_name" => $request->display_itm_name,
                    "mushak_itm_name_bn" => $request->display_itm_name_bn,
                    "uom_id" => $request->uom_id,
                    "current_rate" => $request->current_rate,
                    "safety_level" => $request->safety_level,
                    "reorder_level" => $request->reorder_level,
                    "is_active" => $request->is_active,
                ]);
                $product->item_detail->update([
                    'item_information_id' => $product->id,
                    'description' => $product->description,
                    'description_bn' => $product->description_bn,
                    'item_image' => $request->item_image,
                    'price' => $product->current_rate,
                    'updated_by' => auth()->user()->id,
                ]);
                DB::commit();
                return sendJson('Item Update success', $product, 400);
            } catch (Throwable $th) {
                DB::rollback();
                return sendJson('Item Update failed', $product, 200);
            }
        } else {
            return sendJson('Product Not Found', $product, 400);
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
        $product = VarItemInfo::find($id);
        if ($product) {
            try {
                $product->delete();
                return sendJson('Product Deleted Successfully', null, 400);
            } catch (\Throwable $th) {
                return sendJson('Product Found', $th->getMessage(), 400);
            }
        } else {
            return sendJson('Product Not Found', $product, 400);
        }
    }
}
