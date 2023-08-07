<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\SupplierDetail;
use App\Models\SupplierMapping as ModelsSupplierMapping;
use App\Models\VarItemInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SupplierMapping extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ModelsSupplierMapping::all();
    }

    //init data
    public function init(){
        $sup = SupplierDetail::leftJoin('trns02a_recv_master', 'trns02a_recv_master.supplier_id', 'cs_supplier_details.id')
                ->select(
                    'cs_supplier_details.*',
                    DB::raw('CONCAT(cs_supplier_details.supplier_name,"  ", "(", COUNT(trns02a_recv_master.id),")","  ", "(", SUM(trns02a_recv_master.total_recv_amt_local_curr),"  ","BDT.",")") AS concatenated_value'),
                )
                ->where('is_active', 1)
                ->whereNotIn('trns02a_recv_master.id', function ($query) {
                    $query->select('recv_master_id')
                        ->from('trns52b_pay_to_sup_child');
                    })
                ->groupBy('cs_supplier_details.id')
                ->get();

        $var_item= VarItemInfo::where('prod_type_id',2)->where('is_active',1)->get();

        return response()->json([
            'suplier' => $sup,
            'items' => $var_item
        ]);
    }
    /**
     * change status method 
     * @param $id  and $status
     */
    public function changeStatus($id,$status){
        return ModelsSupplierMapping::where('id',$id)->update(['isActive'=>$status]);
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
       $mp=new ModelsSupplierMapping();
       $mp->sup_id=$request->sup_id;
       $mp->item_id=$request->item_id;
       $mp->save();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ModelsSupplierMapping::leftJoin('var_item_info','var_item_info.id','item_id')
        ->leftJoin('cs_supplier_details','cs_supplier_details.id','supplier_mappings.sup_id')
        ->select('supplier_mappings.id','supplier_name','supplier_mappings.is_active','supplier_name_bn','display_itm_name','display_itm_name_bn','supplier_mappings.sup_id', 'item_id')
        ->where('supplier_mappings.sup_id',$id)->get();
        
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
        return ModelsSupplierMapping::where('id',$id)->delete();
    }
}
