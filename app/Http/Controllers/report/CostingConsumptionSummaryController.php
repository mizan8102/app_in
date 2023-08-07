<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
class CostingConsumptionSummaryController extends Controller
{
    public function costingConsumptionSummary()
    {
        $pdf = PDF::loadView('report.costing_consumption_summary');
        
        return $pdf->stream('costing_consumption_summary.pdf');
    }
}
