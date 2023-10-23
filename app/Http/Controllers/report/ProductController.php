<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use DB;

class ProductController extends Controller
{
    public function productRequisition(){
        $pdf = PDF::loadView('report.productRequisition');
        return $pdf->stream('productRequisition.pdf'); 
    }
    
    public function productRequisitionSummaryPdf(Request $request){
        $from = date('Y-m-d',strtotime($request->from));
        $to = date('Y-m-d',strtotime($request->to));
        $pReqs=DB::select('CALL Report_B_02B_ProductRequisitionSummary("'.$from.'","'.$to.'")');
        $pdf = PDF::loadView('report.requisitionSummery',['pReqs'=>$pReqs],
        [
            'mode'                 => '',
            'format'               => 'A4-L',
            'default_font_size'    => '12',
            'default_font'         => 'sans-serif',
            'margin_left'          => 5,
            'margin_right'         => 5,
            'margin_top'           => 25,
            'margin_bottom'        => 5,
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
        return $pdf->stream('report.requisitionSummery.pdf'); 
    }
    
}
