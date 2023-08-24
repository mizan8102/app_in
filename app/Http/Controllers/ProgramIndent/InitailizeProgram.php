<?php

namespace App\Http\Controllers\ProgramIndent;

use App\Http\Controllers\Controller;
use App\Models\OrderMaster;
use DB;
use Illuminate\Http\Request;
class InitailizeProgram extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $page= $request->page;
        $search = $request->search;
        return OrderMaster::leftJoin('cs_customer_details','cs_customer_details.id','trns00g_order_master.customer_id')
        ->leftJoin('var_program_sessions','var_program_sessions.id','trns00g_order_master.program_session_id')
        ->whereNotNull('trns00g_order_master.program_name')
        ->select(
            'trns00g_order_master.*',
            'cs_customer_details.customer_name',
            'var_program_sessions.session_name',
            DB::raw("DATE_FORMAT(trns00g_order_master.program_date, '%d-%m-%Y') as formatted_program_date")
        )
        ->where('trns00g_order_master.program_name', 'like', "%{$search}%")
        ->paginate(10);
    }
}
