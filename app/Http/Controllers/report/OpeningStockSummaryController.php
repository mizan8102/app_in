<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
class OpeningStockSummaryController extends Controller
{
    public function openingStockSummary()
    {
        $pdf = PDF::loadView('report.opening_stock_summary');

        return $pdf->stream('opening_stock_summary.pdf');
    }
    public function openingBalance(Request $request)
    {
        if ($request->recvMasterId == ''){
            return view('opening_balance_preview');
        }

        $balances=DB::select('CALL Report_C_02A_01_OpeningBalanceInfoByRecvID("'.$request->recvMasterId.'")');
        $pdf = PDF::loadView('report.opening_balance', ['balances' => $balances],
            [
                'mode'                 => '',
                'format'               => 'A4-L',
                'default_font_size'    => '12',
                'default_font'         => 'sans-serif',
                'margin_left'          => 5,
                'margin_right'         => 5,
                'margin_top'           => 25,
                'margin_bottom'        => 15,
                'margin_header'        => 0,
                'margin_footer'        => 0,
                'orientation'          => 'L',
                'title'                => 'Laravel mPDF',
                'author'               => '',
                'watermark'            => '',
                'show_watermark'       => true,
                'watermark_font'       => 'sans-serif',
                'display_mode'         => 'fullpage',
                'watermark_text_alpha' => 0.1,
                'custom_font_dir'      => '',
                'custom_font_data' 	   => [],
                'auto_language_detection'  => false,
                'temp_dir'               => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
                'pdfa' 			=> false,
                'pdfaauto' 		=> false,
            ]
        );
        return $pdf->stream('opening_balance.pdf');
    }

    public function openingBalanceReceive(Request $request)
    {
        //dd($stores);
        if(isset($request->recvMasterId)){
            $balances=DB::select('CALL Report_C_02A_01_OpeningBalanceInfoByRecvID("'.$request->recvMasterId.'")');
            //dd($openings);
            return view('report.opening_balance_preview',compact('balances'));
        }else{
            $balances=[];
            return view('report.opening_balance_preview',compact('balances'));
        }
    }
}
