<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
class KitchenWiseSellController extends Controller
{
    public function kitchenWiseSell()
    {
        $pdf = PDF::loadView('report.kitchen_wise_sell');
        
        return $pdf->stream('kitchen_wise_sell.pdf');
    }
}
