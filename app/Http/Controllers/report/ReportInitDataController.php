<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use App\Models\CsCompanyStoreLocation;
use Illuminate\Http\Request;

class ReportInitDataController extends Controller
{
    public function Store(){
        return CsCompanyStoreLocation::select('id', 'sl_name as no')->get();
    }
}
