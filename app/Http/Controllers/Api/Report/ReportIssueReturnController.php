<?php

namespace App\Http\Controllers\Api\Report;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportIssueReturnController extends Controller
{
    public function index()
    {
        $data = DB::select('CALL issueReturnID()');
        $ir = [];
        foreach ($data as $i => $item) {
            $ir[$i]['id'] = $item->issueReturnId;
            $ir[$i]['no'] = $item->issueReturnNo;
        }
        return $ir;
    }
    public function issueReturnID(){
        return DB::select('CALL Report_C_04A_IssueDetails("' . $request->no . '")');
    }
}
