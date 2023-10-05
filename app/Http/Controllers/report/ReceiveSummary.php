<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class ReceiveSummary extends Controller
{
    public function c0bReceive(){
        // $no = request('no');
        $from=date('Y-m-d',strtotime(request('from','')));
        $to=date('Y-m-d',strtotime(request('to',''))) ;

        return DB::select('call C03B_RecvSummaryGetReceiveDataByDateRange(?,?)',[$from,$to]);
      
    }

  
    
}
