<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\ProductType;
use Illuminate\Http\Request;
use App\Models\ProductCatagory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductTypeController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request()->input('search');
        $perPage = request()->input('perPage', 10);
        $productType = ProductType::query()
            ->when($search, function ($query, $search) {
                return $query->where('prod_type_name', 'like', '%' . $search . '%')
                    ->orWhere('prod_type_name_bn', 'like', '%' . $search . '%');
            })
            ->orderBy('prod_type_name', 'ASC')
            ->paginate($perPage);
        return sendJson('list of the product type', $productType, 200);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $search = request()->input('search');
        $categories = ProductCatagory::query()
            ->when($search, function ($query, $search) {
                return $query->where('prod_cat_name', 'like', '%' . $search . '%');
            })->orderBy('prod_cat_name', 'ASC')->get();
        return sendJson('category list', $categories, 200);
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
            'prod_type_name' => 'required|max:255|unique:5h_sv_product_category,prod_cat_name',
            'prod_type_name_bn' => 'required|max:255|unique:5h_sv_product_category,prod_cat_name_bn',
            'is_active' => 'required|boolean',
        ]);
        if ($validated->fails()) {
            return sendJson('Validation failed', $validated->errors(), 422);
        }
        try {
            DB::beginTransaction();
            $productType = ProductType::create([
                'prod_type_name' => $request->prod_type_name,
                'prod_type_name_bn' => $request->prod_type_name_bn,
                'is_active' => $request->is_active,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
            DB::commit();
            return sendJson('Product type Created Successfully', $productType, 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return sendJson('Product type Created failed', $th->getMessage(), 400);
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
        $productType = ProductType::find($id);
        if ($productType) {
            return sendJson('Product Type found', $productType, 200);
        } else {
            return sendJson('Product Type not found', null, 200);
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
        $productType = ProductType::find($id);
        if ($productType) {
            return sendJson('Product Type found', $productType, 200);
        } else {
            return sendJson('Product Type not found', null, 200);
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
        $productType = ProductType::find($id);
        if ($productType) {
            $validated = Validator::make($request->all(), [
                'prod_type_name' => 'required|max:255|unique:5h_sv_product_category,prod_cat_name',
                'prod_type_name_bn' => 'required|max:255|unique:5h_sv_product_category,prod_cat_name_bn',
                'sequence' => 'required|numeric|regex:/^\d\.\d$/',
                'is_active' => 'required|numeric',
            ]);
            if ($validated->fails()) {
                return sendJson('Validation failed', $validated->errors(), 422);
            }
            try {
                DB::beginTransaction();
                $productType->update([
                    'prod_type_name' => $request->prod_type_name,
                    'prod_type_name_bn' => $request->prod_type_name_bn,
                    'sequence' => $request->sequence,
                    'is_active' => $request->is_active,
                    'updated_by' => auth()->user()->id,
                ]);
                DB::commit();
                return sendJson('Product type updated successfully', $productType, 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                return sendJson('Product type updated failed', $productType, 200);
            }
        } else {
            return sendJson('Sorry product type not found', null, 400);
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
        $productType = ProductType::find($id);
        if ($productType) {
            try {
                $productType->delete();
                return sendJson('Product Type deleted successfully', $productType, 200);
            } catch (\Throwable $th) {
                return sendJson('Product Type deleted failed', $productType, 400);
            }
        } else {
            return sendJson('Product Type not found', null, 200);
        }
    }
}