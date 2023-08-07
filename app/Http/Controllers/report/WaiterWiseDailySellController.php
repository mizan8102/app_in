<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
class WaiterWiseDailySellController extends Controller
{
    public function waiterWisedailySell()
    {
        $pdf = PDF::loadView('report.waiter_wise_daily_sell');
        
        return $pdf->stream('waiter_wise_daily_sell.pdf');

    }
}
