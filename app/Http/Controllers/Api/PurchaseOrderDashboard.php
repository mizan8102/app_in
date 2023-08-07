<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CsCompanyStoreLocation;
use App\Models\ProductCatagory;
use App\Models\PurchaseReqMaster;
use Illuminate\Http\Request;

class PurchaseOrderDashboard extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pendingOrder=PurchaseReqMaster::where('status',0)->count();

        $completeOrder=PurchaseReqMaster::where('status',1)->count();
        return response()->json([
            'pendingOrder' => $pendingOrder,
            'completeOrder' => $completeOrder
        ]);
    }

    /** pending order purchase */

    public function pendingOrder(){




        $search = request('search', '');
        $perPage = request('perPage', 10);
        $masterGroup=request('masterGroup');

        $store=CsCompanyStoreLocation::select('id','sl_name')->get();
        $pro_cat=ProductCatagory::select('id','prod_cat_name')->get();

        $purchaseData= PurchaseReqMaster::join('trns00a_indent_master','trns00a_indent_master.id','=','trns00c_purchase_req_master.indent_master_id')
        ->join('p_program_master','trns00a_indent_master.program_master_id','=','p_program_master.id')
        ->leftJoin('program_sessions','p_program_master.program_session_id','=','program_sessions.id')
        -> select('p_program_master.program_name','indent_number','p_program_master.prog_date','p_program_master.prog_start_time','number_of_guest','p_program_master.prog_end_time','indent_date',
        'trns00c_purchase_req_master.*','program_sessions.session_name',
        'program_sessions.start_time',
        'program_sessions.end_time')->where('trns00c_purchase_req_master.status',0)
        ->where('p_program_master.program_name', 'like', "%{$search}%")
        ->paginate($perPage);
        return Helper::sendJson([
            'store' => $store,
            'pro_catagroy'=> $pro_cat,
            'purchaseData'=> $purchaseData
        ]);
    }
    public function pendOrderServer(){
        $search = request('query', '');
        $perPage = request('page', 10);
        // $orderBy = request('limit', 10);
           return PurchaseReqMaster::join('trns00a_indent_master','trns00a_indent_master.id','=','trns00c_purchase_req_master.indent_master_id')
        ->join('p_program_master','trns00a_indent_master.program_master_id','=','p_program_master.id')
        -> select('p_program_master.program_name','indent_number','p_program_master.prog_start_time','number_of_guest','p_program_master.prog_end_time','indent_date',
        'trns00c_purchase_req_master.*')->where('trns00c_purchase_req_master.status',0)
        ->where('p_program_master.program_name', 'like', "%{$search}%")
        ->paginate($perPage);
    }

    public function approvedOrder(){
        $search = request('search', '');
        $perPage = request('perPage', 10);
        return PurchaseReqMaster::join('trns00a_indent_master','trns00a_indent_master.id','=','trns00c_purchase_req_master.indent_master_id')
        ->join('p_program_master','trns00a_indent_master.program_master_id','=','p_program_master.id')
        ->leftJoin('program_sessions','p_program_master.program_session_id','=','program_sessions.id')
        -> select('p_program_master.program_name','indent_number','p_program_master.prog_start_time','number_of_guest','p_program_master.prog_end_time','indent_date',
        'trns00c_purchase_req_master.*','program_sessions.session_name',
        'program_sessions.start_time',
        'program_sessions.end_time')->where('trns00c_purchase_req_master.status',1)
        ->where('p_program_master.program_name', 'like', "%{$search}%")
        ->paginate($perPage);
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
