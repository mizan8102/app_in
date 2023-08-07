<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\DB;
class IssueReturnSummaryController extends Controller
{
    public function issueReturnSummary(Request $request)
    {
        // $pdf = PDF::loadView('issue_return_summary');
        
        // return $pdf->stream('issue_return_summary.pdf');
        // $returnID = 1;

        $returns = DB::select('CALL Report_C_05C_IssueReturnSummary("'.$request->fromDate.'","'.$request->toDate.'")');
        $pdf = PDF::loadView('issue_return_summary', compact('returns'));
        
        return $pdf->stream('issue_return_summary.pdf');

    }

    public function issueReturnSum(Request $request)
    {
        if(isset($request->fromDate) && isset($request->toDate)){
            $returns=DB::select('CALL Report_C_05C_IssueReturnSummary("'.$request->fromDate.'","'.$request->toDate.'")');
            //dd($returns);
            return view('issue_return_summary_preview',compact('returns'));
        }else{
            $returns=[];
            return view('issue_return_summary_preview',compact('returns'));
        }
    }
}
