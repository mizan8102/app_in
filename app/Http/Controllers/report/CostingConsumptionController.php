<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
class CostingConsumptionController extends Controller
{
    public function costingConsumption()
    {
        $pdf = PDF::loadView('report.costing_consumption');
        
        return $pdf->stream('costing_consumption.pdf');
    }
}
