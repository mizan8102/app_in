<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class ReportApiController extends Controller
{
    public function itemWise(Request $request)
    {
        // return $request;
        $from =  Date('Y-m-d',strtotime($request->from));
        $to =  Date('Y-m-d',strtotime($request->to));
        
        if(isset($request->from) && isset($request->to)){
           return DB::select('CALL Report_A_01_ItemWiseDailySell("'.$from.'","'.$to.'")');
          
        }else{
           return [];
        }
    }

    public function A_02_order_wise_daily(Request $request){
        $from =  Date('Y-m-d',strtotime($request->from));
        $to =  Date('Y-m-d',strtotime($request->to));
        $store= $request->no;
        if(isset($request->from) && isset($request->to)){
           return DB::select('CALL Report_A_02_OrderWiseDailySell("'.$from.'","'.$to.'","'.$store.'")');
          
        }else{
           return response()->json(['data' =>[]]) ;
        }
    }


}
