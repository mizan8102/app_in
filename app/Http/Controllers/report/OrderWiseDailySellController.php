<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
class OrderWiseDailySellController extends Controller
{
    public function orderWiseDailySell()
    {
        $pdf = PDF::loadView('report.order_wise_daily_sell');
        
        return $pdf->stream('item_wise_daily_sell.pdf');
    }
}
