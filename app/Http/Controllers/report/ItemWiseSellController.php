<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF; 
class ItemWiseSellController extends Controller
{
    public function itemWiseSell()
    {
        $pdf = PDF::loadView('report.item_wise_sell');
        
        return $pdf->stream('item_wise_sell.pdf');
    }
}
