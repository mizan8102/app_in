<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;

class ReceiveController extends Controller
{
    public function receiveSummery(){
        $pdf = PDF::loadView('report.receiveSummery');
        return $pdf->stream('receiveSummery.pdf'); 
    }
}
