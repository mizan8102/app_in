<?php

namespace App\Http\Controllers\ProgramIndent;

use App\Http\Controllers\Controller;
use App\Models\OrderChild;
use App\Models\OrderMaster;
use Illuminate\Http\Request;

class ProgramIndentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function programInit($id) {
        $data = OrderChild::leftJoin('var_item_info', 'trns00h_order_child.item_info_id', 'var_item_info.id')
        ->with([
            'item_info.priceDeclarationItem'
        ])
        ->where('var_item_info.prod_type_id', 3)
        ->where('trns00h_order_child.order_master_id', $id)
        ->get();
    
        
    return $data;
    
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
