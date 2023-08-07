<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramResource;
use App\Models\ItemMasterModel;
use App\Models\PProgramMaster;
use Illuminate\Http\Request;

class IndentDashboard extends Controller
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

        return ProgramResource::collection(PProgramMaster::select('p_program_master.*','floor_name',
        'customer_name','phone_number', 'program_sessions.session_name',
        'program_sessions.start_time',
        'program_sessions.end_time')->join('r_floor','r_floor.id','=','p_program_master.floor_id')
        ->leftJoin('program_sessions','p_program_master.program_session_id','=','program_sessions.id')
        ->join('cs_customer_details','p_program_master.customer_id','=','cs_customer_details.id')
        ->leftjoin('program_pay_details','p_program_master.id','=','program_pay_details.p_program_master_id')
        ->where('p_program_master.status',0)
        ->where('p_program_master.program_name', 'like', "%{$search}%")
        ->orderBy('prog_date', 'asc')
        ->paginate($perPage));
    }
    public function indexCompleteProgram()
    {

        $search = request('search', '');
        $perPage = request('perPage', 10);

        return ProgramResource::collection(PProgramMaster::select('p_program_master.*','floor_name',
        'customer_name','phone_number',  'program_sessions.session_name',
        'program_sessions.start_time',
        'program_sessions.end_time')->join('r_floor','r_floor.id','=','p_program_master.floor_id')
        ->join('cs_customer_details','p_program_master.customer_id','=','cs_customer_details.id')
        ->leftJoin('program_sessions','p_program_master.program_session_id','=','program_sessions.id')
        ->leftjoin('program_pay_details','p_program_master.id','=','program_pay_details.p_program_master_id')
        ->where('p_program_master.status',1)
        ->where('p_program_master.program_name', 'like', "%{$search}%")
        ->orderBy('prog_date', 'asc')
        ->paginate($perPage));
    }

    public function CardData(){
        $pendingData=ItemMasterModel::where('close_status',0)->count();
        $endgData=ItemMasterModel::where('close_status',1)->count();
        return Helper::sendJson([
            'pendingIndent' =>$pendingData,
            'completIndent' => $endgData
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
