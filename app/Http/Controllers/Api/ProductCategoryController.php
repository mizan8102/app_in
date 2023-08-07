<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductCatagory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
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
        $categories = ProductCatagory::query()
            ->when($search, function ($query, $search) {
                return $query->where('prod_cat_name', 'like', '%' . $search . '%');
            })
            ->orderBy('prod_cat_name', 'ASC')
            ->paginate($perPage);
        return sendJson('list of the category', $categories, 200);
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
        $request->validate([
            'prod_cat_name' => 'required|max:255|unique:5h_sv_product_category,prod_cat_name',
            'prod_cat_name_bn' => 'required|max:255|unique:5h_sv_product_category,prod_cat_name_bn',
            // 'is_active' => 'required|boolean',
        ]);
        try {
            DB::beginTransaction();
            $category = ProductCatagory::create([
                'prod_cat_name' => $request->prod_cat_name,
                'prod_cat_name_bn' => $request->prod_cat_name_bn,
                // 'is_active' => $request->is_active,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
            DB::commit();
            return sendJson('Category Created Successfully', $category, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendJson('Category Created failed', $th->getMessage(), 400);
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
        $category = ProductCatagory::find($id);
        if ($category) {
            return sendJson('Sorry category found', $category, 400);
        } else {
            return sendJson('Sorry category not found', null, 400);
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
        $category = ProductCatagory::find($id);
        if ($category) {
            return sendJson('Category found', $category, 200);
        } else {
            return sendJson('Sorry category not found', null, 400);
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
        $category = ProductCatagory::find($id);
        if ($category) {
            $validated = Validator::make($request->all(), [
                'prod_cat_name' => 'required|max:255|unique:5h_sv_product_category,prod_cat_name,except,id',
                'prod_cat_name_bn' => 'required|max:255|unique:5h_sv_product_category,prod_cat_name_bn,except,id',
                'sequence' => 'required|numeric|regex:/^\d\.\d$/',
                'is_active' => 'required|numeric',
            ]);
            if ($validated->fails()) {
                return sendJson('Validation failed', $validated->errors(), 422);
            }
            try {
                DB::beginTransaction();
                $category->update([
                    'prod_cat_name' => $request->prod_cat_name,
                    'prod_cat_name_bn' => $request->prod_cat_name_bn,
                    'sequence' => $request->sequence,
                    'is_active' => $request->is_active,
                    'updated_by' => auth()->user()->id,
                ]);
                DB::commit();
                return sendJson('Category updated successfully', $category, 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                return sendJson('Category updated failed', $category, 200);
            }
        } else {
            return sendJson('Sorry category not found', null, 400);
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
        $category = ProductCatagory::find($id);
        if ($category) {
            try {
                DB::beginTransaction();
                $category->delete();
                DB::commit();
                return sendJson('Category deleted successfully', null, 200);
            } catch (\Throwable $th) {
                return sendJson('Category deleted failed', $category, 200);
            }
        } else {
            return sendJson('Sorry category not found', null, 400);
        }
    }
}
