<?php

namespace App\Http\Controllers\Api\Transfer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreLocationController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = DB::table('cs_company_store_location')
        ->select('id','branch_id','sl_name')->get();
        return response()->json($data,200);
    }
}
