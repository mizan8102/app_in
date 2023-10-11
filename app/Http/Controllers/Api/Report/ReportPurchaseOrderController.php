<?php

namespace App\Http\Controllers\Api\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ReportPurchaseOrderController extends Controller
{
    public function getPOList()
    {
        $data = DB::select('CALL GetPurchaseOrderID()');
        $pos = [];
        foreach ($data as $i => $item) {
            $pos[$i]['id'] = $item->pOrderNo;
            $pos[$i]['no'] = $item->pOrderNo;
        }
        return $pos;
    }
    public function getPOById(Request $request)
    {
        return DB::select('CALL Report_B_04A_PurchaseOrder("' . $request->no . '")');
    }
    public function getPOByIdPDF(Request $request)
    {
        $pos =
            DB::select('CALL Report_B_04A_PurchaseOrder("' . $request->no . '")');
        $pdf = PDF::loadView(
            'report.purchaseOrde',
            ['pos' => $pos],
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

        return $pdf->stream('report.purchaseOrde');
    }
    public function getPOSummaryByDate(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from));
        $to = date('Y-m-d', strtotime($request->to));
        return $pos = DB::select('CALL Report_B_04B_PurchaseOrderSummary("' . $from . '","' . $to . '")');
    }
    public function getPOSummaryByDatePDF(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from));
        $to = date('Y-m-d', strtotime($request->to));
        $pos = DB::select('CALL Report_B_04B_PurchaseOrderSummary("' . $from . '","' . $to . '")');
        $pdf = PDF::loadView(
            'report.purchaseOrderSummery',
            ['pos' => $pos],
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
        return $pdf->stream('report.purchaseOrderSummery');
    }
}
