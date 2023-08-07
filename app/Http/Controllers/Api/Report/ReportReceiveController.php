<?php

namespace App\Http\Controllers\Api\Report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use PDF;

class ReportReceiveController extends Controller
{
    public function getGRNNo()
    {
        $data = DB::select('CALL GetGRNNo()');
        $grn = [];
        foreach ($data as $i => $item) {
            $grn[$i]['id'] = $item->grnNo;
            $grn[$i]['no'] = $item->grnNo;
        }
        return $grn;
    }
    public function getRecvByGRNNo(Request $request)
    {
        return DB::select('CALL Report_C_03A_Receive("' . $request->no . '")');
    }
    public function getRecvByGRNNoPDF(Request $request)
    {
        $grn =
            DB::select('CALL Report_C_03A_Receive("' . $request->no . '")');
        $pdf = PDF::loadView(
            'report.receive_report',
            ['grn' => $grn],
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

        return $pdf->stream('report.receive_report');
    }
    public function getRecvSummaryByDate(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from));
        $to = date('Y-m-d', strtotime($request->to));
        return $grn = DB::select('CALL Report_C_03B_ReceiveSummary("' . $from . '","' . $to . '")');
    }
    public function getRecvSummaryByDatePDF(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from));
        $to = date('Y-m-d', strtotime($request->to));
        $grn = DB::select('CALL Report_C_03B_ReceiveSummary("' . $from . '","' . $to . '")');
        $pdf = PDF::loadView(
            'report.receiveSummery',
            ['grn' => $grn],
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
        return $pdf->stream('report.receiveSummery');
    }
}
