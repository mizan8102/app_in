<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;

class ItemController extends Controller
{
    public function itemStockSummery(){
        $pdf = PDF::loadView('report.itemStockSummery');
        return $pdf->stream('itemStockSummery.pdf'); 
    }
}
