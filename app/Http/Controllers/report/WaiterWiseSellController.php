<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
class WaiterWiseSellController extends Controller
{
    public function waiterWiseSell()
    {

        $pdf = PDF::loadView('report.waiter_wise_sell');
        
        return $pdf->stream('waiter_wise_sell.pdf');
    }
}
