<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\CsCompanyBranch;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CsCompanyStoreLocation;
use Illuminate\Support\Facades\Validator;

class StoreLocationController extends Controller
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
        $store = CsCompanyStoreLocation::leftJoin('cs_company_branch_unit','cs_company_branch_unit.id','cs_company_store_location.branch_id')->
        where('sl_name', 'like', "%{$search}%")
            ->where('sl_name_bn', 'like', "%{$search}%")
            ->where('sl_type', 'like', "%{$search}%")
            ->select('cs_company_store_location.*','cs_company_branch_unit.b_u_name as b_u_name')
            ->paginate($perPage);
        return sendJson('store location list', $store, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            'branch' => CsCompanyBranch::where('is_active', 1)->select('id', 'b_u_name', 'b_u_name_bn')->get(),
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
        $request->validate([
            'branch_id' => 'required|exists:cs_company_branch_unit,id',
            'sl_name' => 'required|unique:cs_company_store_location,sl_name,except,id',
            'sl_name_bn' => 'required|unique:cs_company_store_location,sl_name_bn,except,id',
            'sl_type' => 'required|max:255',
            'is_sales_point'=>'required|boolean',
            'is_default_location'=>'required|boolean',
            'is_virtual_location'=>'required|boolean',
        ]);
        try {
            DB::beginTransaction();
            $storeLocation = CsCompanyStoreLocation::create([
                'branch_id' => $request->branch_id,
                'sl_name' => $request->sl_name,
                'sl_name_bn' => $request->sl_name_bn,
                'sl_type' => $request->sl_type,
                'is_active' => $request->is_active,
                'is_default_location' => $request->is_default_location,
                'is_sales_point' => $request->is_sales_point,
                'is_virtual_location' => $request->is_virtual_location,
            ]);
            DB::commit();
            return sendJson('store location', $storeLocation, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendJson('store location', $th->getMessage(), 500);
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
        $storeLocation = CsCompanyStoreLocation::find($id);
        return sendJson('Store location', $storeLocation, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $storeLocation = CsCompanyStoreLocation::find($id);
        return sendJson('Store location', $storeLocation, 200);
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
        $storeLocation = CsCompanyStoreLocation::find($id);
        $request->validate([
            'branch_id' => 'required|exists:cs_company_branch_unit,id',
            'sl_name' => 'required|unique:cs_company_store_location,sl_name,except,id',
            'sl_name_bn' => 'required|unique:cs_company_store_location,sl_name_bn,except,id',
            'sl_type' => 'required|max:255',
            'is_sales_point'=>'required|boolean',
            'is_default_location'=>'required|boolean',
            'is_virtual_location'=>'required|boolean',
        ]);
        try {
            DB::beginTransaction();
            $storeLocation = CsCompanyStoreLocation::create([
                'branch_id' => $request->branch_id,
                'sl_name' => $request->sl_name,
                'sl_name_bn' => $request->sl_name_bn,
                'sl_type' => $request->sl_type,
                'is_active' => 1,
            ]);
            DB::commit();
            return sendJson('store location', $storeLocation, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendJson('store location', $th->getMessage(), 500);
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
            $storeLocation = CsCompanyStoreLocation::find($id)->delete();
            return sendJson(' success delete', $storeLocation, 200);
        } catch (\Throwable $th) {
            return sendJson(' failed', $th->getMessage(), 500);
        }
    }
}