<?php

namespace App\Http\Controllers\FiscalYearMonth;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\FiscalYearInfo;

class FiscalyearMonthController extends Controller
{
    public function index(){
        $date=date('Y-m-d',strtotime(request('date',date('Y-m-d'))));
        $fas_cal_info= FiscalYearInfo::select('vm_info','5x2_vat_month_info.id as vat_month_id','5x1_fiscal_year_info.id as fiscal_id','fsc_year_info')
        ->leftJoin('5x2_vat_month_info','5x2_vat_month_info.fy_id','5x1_fiscal_year_info.id')
        ->where('5x1_fiscal_year_info.to_date', '>=', $date)
        ->where('5x1_fiscal_year_info.from_date', '<=', $date) ->orderBy('5x2_vat_month_info.id', 'desc')
        ->first();
        return response()->json([
            'fiscal_info' => $fas_cal_info
        ]);
       
    }
    public function currency(){
        $currency = Currency::orderBy('currency_shortcode')->get();
        return $currency;
    }
}