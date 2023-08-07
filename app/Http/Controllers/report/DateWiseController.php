<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class DateWiseController extends Controller
{
    public function datewise(Request $request)
    {
    //    $productID = DB::select('CALL GetOrderWiseBooksReport("")');
        //dd($productID);
        // $productID = DB::Table('var_item_info')->get();
        //dd($productID);
        // return view('datepicker',['productID' => $productID]);
        return view ('report.datepicker');
        
        // $input = $request->all();
        // $fromdate = $request->$input['fromDate'];
        // $toDate = $request->$input ['toDate'];
        // return  "Date: " . $fromDate ."To date ".toDate;
    }
}