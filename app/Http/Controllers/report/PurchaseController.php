<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;

class PurchaseController extends Controller
{
    public function purchaseRequisition(){
        $pdf = PDF::loadView('report.purchaseRequisition');
        return $pdf->stream('purchaseRequisition.pdf'); 
    }
    public function purchaseRequisitionSummery(){
        $pdf = PDF::loadView('report.purchaseRequisitionSummery');
        return $pdf->stream('purchaseRequisitionSummery.pdf'); 
    }
    public function purchaseOrder(){
        $pdf = PDF::loadView('report.purchaseOrde');
        return $pdf->stream('purchaseOrde.pdf'); 
    }
    public function purchaseOrderSummery(){
        $pdf = PDF::loadView('report.purchaseOrderSummery');
        return $pdf->stream('purchaseOrderSummery.pdf'); 
    }
}
