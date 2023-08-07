<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
class ReceiveReportController extends Controller
{
    public function receiveReport()
    {
        $pdf = PDF::loadView('report.receive_report');
        
        return $pdf->stream('receive_report.pdf');
    }
}
