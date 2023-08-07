<?php

namespace App\Http\Controllers\Api\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ProductRequisitionController extends Controller
{
    public function getProductRequisitionNo(Request $request)
    {
        $CloseStatus = 0;
        $IsProductReq = 1;
        $data = DB::select('CALL GetIndentID("' . $CloseStatus . '","' . $IsProductReq . '")');
        $indents = [];
        foreach ($data as $i => $item) {
            $indents[$i]['id'] = $item->indentNo;
            $indents[$i]['no'] = $item->indentNo;
        }
        return $indents;
    }
    public function getProductRequisitionByNo(Request $request)
    {
        return $items = DB::select('CALL Report_B_02A_ProductRequisition("' . $request->no . '")');
    }
    public function getProductRequisitionByNoPDF(Request $request)
    {
        $indents = DB::select('CALL Report_B_02A_ProductRequisition("' . $request->no . '")');
        
        $pdf = PDF::loadView(
            'report.productRequisition',
            ['indents' => $indents,'indent_no'=>$request->no],
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
                'show_watermark'       => false,
                'watermark_font'       => 'sans-serif',
                'display_mode'         => 'fullpage',
                'watermark_text_alpha' => 0.1,
                'custom_font_dir'      => '',
                'custom_font_data'        => [],
                'auto_language_detection'  => false,
                'temp_dir'               => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
                'pdfa'             => false,
                'pdfaauto'         => false,
            ]
        );

        return $pdf->stream('report.productRequisition');
    }
    public function productRequisitionSummaryByDate(Request $request){
        $from = date('Y-m-d',strtotime($request->from));
        $to = date('Y-m-d',strtotime($request->to));
        return $items=DB::select('CALL Report_B_02B_ProductRequisitionSummary("'.$from.'","'.$to.'")');
    }
    public function productRequisitionSummaryByDatePDF(Request $request){
        $from = date('Y-m-d',strtotime($request->from));
        $to = date('Y-m-d',strtotime($request->to));
        $reqData['from_date'] = date('d-m-Y',strtotime($request->from));
        $reqData['to_date'] = date('d-m-Y',strtotime($request->to));
        $indents = DB::select('CALL Report_B_02B_ProductRequisitionSummary("'.$from.'","'.$to.'")');

        $pdf = PDF::loadView('report.requisitionSummery',['indents'=>$indents,'reqData'=>$reqData],
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
                'show_watermark'       => false,
                'watermark_font'       => 'sans-serif',
                'display_mode'         => 'fullpage',
                'watermark_text_alpha' => 0.1,
                'custom_font_dir'      => '',
                'custom_font_data'     => [],
                'auto_language_detection'  => false,
                'temp_dir'               => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
                'pdfa'          => false,
                'pdfaauto'      => false,
            ]
        );
        return $pdf->stream('report.requisitionSummery');
    }
}