<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;

class ProductController extends Controller
{
    public function productRequisition(){
        $pdf = PDF::loadView('report.productRequisition');
        return $pdf->stream('productRequisition.pdf'); 
    }
    public function requisitionSummery(){
        $pdf = PDF::loadView('report.requisitionSummery');
        return $pdf->stream('requisitionSummery.pdf'); 
    }
}
