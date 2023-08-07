<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF; 
class ChikleeDemoReportController extends Controller
{
    public function chiklee()
    {
        $pdf = PDF::loadView('report.chiklee');
        
        return $pdf->stream('chiklee_report.pdf',);
    }
}
