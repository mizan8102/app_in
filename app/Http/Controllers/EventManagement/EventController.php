<?php

namespace App\Http\Controllers\EventManagement;

use App\Http\Controllers\Controller;
use App\Models\OrderMaster;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today_programes = OrderMaster::select('trns00g_order_master.*',
        'cs_company_store_location.sl_name as floor_name','cs_customer_details.customer_name','var_program_sessions.session_name','var_program_sessions.start_time',
        'var_program_sessions.end_time')
        ->leftJoin('cs_company_store_location','cs_company_store_location.id','trns00g_order_master.floor_id')
        ->leftJoin('cs_customer_details','cs_customer_details.id','trns00g_order_master.customer_id')
        ->leftJoin('var_program_sessions','var_program_sessions.id','trns00g_order_master.program_session_id')
        ->whereDate('program_date', date('Y-m-d'))
                    ->get();
        $upcoming_programs=OrderMaster::select('trns00g_order_master.*',
        'cs_company_store_location.sl_name as floor_name','cs_customer_details.customer_name','var_program_sessions.session_name','var_program_sessions.start_time',
        'var_program_sessions.end_time')
        ->leftJoin('cs_company_store_location','cs_company_store_location.id','trns00g_order_master.floor_id')
        ->leftJoin('cs_customer_details','cs_customer_details.id','trns00g_order_master.customer_id')
        ->leftJoin('var_program_sessions','var_program_sessions.id','trns00g_order_master.program_session_id')
        ->whereDate('program_date','>',date('Y-m-d'))->get();
        return response()->json([
            'today_programs' => $today_programes,
            'upcoming_programes' => $upcoming_programs
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
        $dds=OrderMaster::with([""])->find($id);
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
