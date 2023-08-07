<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
class IssueController extends Controller
{
    public function issueDetails(){
        $pdf = PDF::loadView('report.issueDetails');
        return $pdf->stream('issueDetails.pdf'); 
    }
    public function issueSummery(){
        $pdf = PDF::loadView('report.issueSummery');
        return $pdf->stream('issueSummery.pdf'); 
    }
    public function issueReturn(){
        $pdf = PDF::loadView('report.issueReturn');
        return $pdf->stream('issueReturn.pdf'); 
    }
}
