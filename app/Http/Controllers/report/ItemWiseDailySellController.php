<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
class ItemWiseDailySellController extends Controller
{
        public function itemWiseDailySell()
    {
        $pdf = PDF::loadView('report.item_wise_daily_sell');

        return $pdf->stream('item_wise_daily_sell.pdf'); 

    }
}
