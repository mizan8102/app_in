<?php

namespace App\Http\Controllers\Requisition;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ItemMasterModel;
use App\Models\PurchaseReqMaster;
use Illuminate\Http\Request;

class RequisitionDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array|object
     */
    public function index()
    {
        $pendingRequsition=ItemMasterModel::where('status',0)->count();
        $approvedRequisition=ItemMasterModel::where('status',1)->count();
        $partialRequisition=PurchaseReqMaster::where('is_partial',1)->count();
        $ar=[
            'pendingRequistion' => $pendingRequsition,
            'approvedRequisition' => $approvedRequisition,
            'partial' => $partialRequisition
        ];
        return Helper::sendJson($ar);

    }

    /**
     * partial pending
     */
    public function partialRequisition(){
        $search = request('search', '');
        $perPage = request('perPage', 10);
        return PurchaseReqMaster::join('trns00a_indent_master','trns00a_indent_master.id','=','trns00c_purchase_req_master.indent_master_id')
        ->join('p_program_master','trns00a_indent_master.program_master_id','=','p_program_master.id')
        ->leftJoin('program_sessions','p_program_master.program_session_id','=','program_sessions.id')
        -> select('p_program_master.program_name','indent_number','p_program_master.prog_start_time','number_of_guest','p_program_master.prog_end_time','indent_date',
        'trns00c_purchase_req_master.*','program_sessions.session_name',
        'program_sessions.start_time',
        'program_sessions.end_time')->where('trns00c_purchase_req_master.is_partial',1)
        ->where('p_program_master.program_name', 'like', "%{$search}%")
        ->paginate($perPage);
    }

    /** pending requisition
     *
     */

     public function pendingRequisition(){
        $search = request('search', '');
        $perPage = request('perPage', 10);

        return ItemMasterModel::join('p_program_master','trns00a_indent_master.program_master_id','=','p_program_master.id')
        ->leftJoin('program_sessions','p_program_master.program_session_id','=','program_sessions.id')
        -> select('p_program_master.program_name','indent_number','p_program_master.prog_start_time','number_of_guest','p_program_master.prog_end_time',
        'indent_date',
        'trns00a_indent_master.*','program_sessions.session_name',
        'program_sessions.start_time',
        'program_sessions.end_time')->where('trns00a_indent_master.status',0)
        ->where('p_program_master.program_name', 'like', "%{$search}%")
        ->paginate($perPage);

     }

     // approved requisition
     public function approvedRequisition(){
        $search = request('search', '');
        $perPage = request('perPage', 10);
        return ItemMasterModel::join('p_program_master','trns00a_indent_master.program_master_id','=','p_program_master.id')
        ->leftJoin('program_sessions','p_program_master.program_session_id','=','program_sessions.id')
        -> select('p_program_master.program_name','indent_number','p_program_master.prog_start_time','number_of_guest','p_program_master.prog_end_time',
        'indent_date',
        'trns00a_indent_master.*','program_sessions.session_name',
        'program_sessions.start_time',
        'program_sessions.end_time')->where('trns00a_indent_master.status',1)
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
