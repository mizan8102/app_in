<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductCatagory;
use App\Models\SubGroup;
use Illuminate\Http\Request;
use App\Models\SvUOM;
use Illuminate\Support\Facades\DB;

class UOMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $items = SvUOM::select("id as uom_id", "uom_desc", "uom_short_code", "local_desc")
        ->get();
    }

    public function getUoms(){
        $items = SvUOM::select("id as uom_id", "uom_desc", "uom_short_code", "local_desc")
        ->get();
        $var_sub= DB::table('5f_sv_product_type')
        ->select(
            '5f_sv_product_type.id AS itm_sub_grp_id',
            '5f_sv_product_type.prod_type_name AS itm_sub_grp_des',
            // 'var_item_sub_group.itm_sub_grp_des_bn',
        )
        
        ->get();
        $sub_group=SubGroup::all();
        $pro_cat=ProductCatagory::all();
        return response()->json([
            'items' => $items,
            'var_sub' => $var_sub,
            'prod_cat' => $pro_cat,
            'sub_grp' => $sub_group
        ]);
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
        //
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
        //
    }
}
