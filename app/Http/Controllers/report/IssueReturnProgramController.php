<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
class IssueReturnProgramController extends Controller
{
    public function issueReturnProgram()
    {
        $pdf = PDF::loadView('report.issue_return_program');
        
        return $pdf->stream('issue_return_program.pdf');
    }
}
