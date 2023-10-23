<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use DB;

class PurchaseController extends Controller
{
    public function purchaseRequisitionSummery(Request $request){

        $from = date('Y-m-d',strtotime($request->from));
        $to = date('Y-m-d',strtotime($request->to));

        $pReqSum = DB::select('CALL Report_B_03B_PurchaseRequisitionSummary("'.$from.'","'.$to.'")');
        $pdf = PDF::loadView('report.purchaseRequisitionSummaryPdf', ['pReqSum' => $pReqSum],
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
        return $pdf->stream('report.purchaseRequisitionSummaryPdf.pdf');
    }








    public function purchases(){
        // $no = request('no');
        $from=date('Y-m-d',strtotime(request('from','')));
        $to=date('Y-m-d',strtotime(request('to',''))) ;

        return DB::select('call Report_B_03B_PurchaseRequisitionSummary(?,?)',[$from,$to]);
    }


    // purchase order summary
    public function purchaseOrderSummaryPdf(Request $request){

        $from =  Date('Y-m-d',strtotime($request->from));
        $to =  Date('Y-m-d',strtotime($request->to));
        
        $pOrders=DB::select('CALL Report_B_04B_PurchaseOrderSummary("'.$from.'","'.$to.'")');
        $pdf = PDF::loadView('report.purchaseOrderSummaryPdf',['pOrders'=>$pOrders],
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
        return $pdf->stream('report.purchaseOrderSummaryPdf.pdf');
    }
}
