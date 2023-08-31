<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use DB;
class ItemWiseDailySellController extends Controller
{
    
    public function itemWiseDailySell(Request $request)
    {

        $items = DB::select('CALL Report_A_01_ItemWiseDailySell("'.date('Y-m-d',strtotime($request->from)).'","'.date('Y-m-d',strtotime($request->to)).'")');
        $pdf = PDF::loadView('report.item_wise_daily_sell', ['items' => $items,'from' =>$request->from, 'to' => $request->to],
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
        
        return $pdf->stream('report.item_wise_daily_sell.pdf');
    }

    public function itemWise(Request $request)
        {
            // return $request;
            $from =  Date('Y-m-d',strtotime($request->from));
            $to =  Date('Y-m-d',strtotime($request->to));
            
            if(isset($request->from) && isset($request->to)){
               return  DB::select('CALL Report_A_01_ItemWiseDailySell("'.$from.'","'.$to.'")');
                // dd($items);
                
            }else{
                $items=[];
                return view('item_wise_preview',compact('items'));
            }
        }
}
