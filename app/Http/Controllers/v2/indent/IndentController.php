<?php

namespace App\Http\Controllers\v2\indent;

use App\Http\Controllers\Controller;
use App\Models\dbview\ItemIndentStockWise;
use App\Models\ItemMasterModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndentController extends Controller
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

    public  function barCodeComeItemForIndent(){
        $masterGroup=request('masterGroup');
        $itemCode=request('itemCode');
        return DB::select('CALL GetItemInfoByItemCode("'.$itemCode.'","'.$masterGroup.'");');
    }
    // individual data added show data
    public function itemIndentStockWise(Request $request){
        $masterId=$request->master_id;
        $search = $request->search;
        $perPage = $request->perPage |0;
        $selected = $request->selected ;
        $data = DB::select('CALL ItemStockStoreWise("'.Auth::user()->store_id.'","'.$masterId.'")');
        return collect($data)->whereNotIn('itmID',$selected)->values();
    }
    public function  itemShowforIndent(Request $request){
         $id=$request->itemNumber;
        $itm= ItemIndentStockWise::whereIn('itmID',$id)->get();
        $result=[];
        $i=0;
        foreach ($itm as $it){
            $result[$i]['sub_grp']= $it->subGroupId;
            $result[$i]['grp']= $it->groupId;
            $result[$i]['itm']= $it->itmID;
            $result[$i]['uom_id']= $it->uomId;
            $result[$i]['display_itm_name']= $it->display_itm_name;
            $result[$i]['display_itm_name_bn']= $it->display_itm_name_bn;
            $result[$i]['indent_quantity']= "";
            $result[$i]['stock']= $it->itmStckQty;
            $result[$i]['uom_short_code']= $it->uom;
            $result[$i]['required_data']= date('d-m-Y');
            $result[$i]['comments']= "";
            $i++;
        }
        return $result;
    }
    public function  closeIndent($id){
        return ItemMasterModel::where('id',$id)->update(['close_status'=>1]);
    }
}
