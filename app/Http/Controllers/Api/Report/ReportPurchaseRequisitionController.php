<?php

namespace App\Http\Controllers\Api\Report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use PDF;

class ReportPurchaseRequisitionController extends Controller
{
    public function getPurchaseRequisitionNo()
    {
        $data = DB::select('CALL GetPurchasesID()');
        $preq = [];
        foreach ($data as $i => $item) {
            $preq[$i]['id'] = $item->pReqNo;
            $preq[$i]['no'] = $item->pReqNo;
        }
        return $preq;
    }
    public function getPurchaseRequisitionByNo(Request $request)
    {
        return $items = DB::select('CALL Report_B_03A_PurchaseRequisition("' . $request->no . '")');
    }
    public function getPurchaseRequisitionPDFByNo(Request $request)
    {
        $preqs = DB::select('CALL Report_B_03A_PurchaseRequisition("' . $request->no . '")');
        $pdf = PDF::loadView(
            'report.purchaseRequisition',
            ['preqs' => $preqs],
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

        return $pdf->stream('report.purchaseRequisition');
    }
    public function purchaseRequisitionSummaryByDate(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from));
        $to = date('Y-m-d', strtotime($request->to));
        return $items = DB::select('CALL Report_B_03B_PurchaseRequisitionSummary("' . $from . '","' . $to . '")');
    }
    public function purchaseRequisitionSummaryByDatePDF(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from));
        $to = date('Y-m-d', strtotime($request->to));
        $reqData['from_date'] = date('d-m-Y', strtotime($request->from));
        $reqData['to_date'] = date('d-m-Y', strtotime($request->to));
        $preqs = DB::select('CALL Report_B_03B_PurchaseRequisitionSummary("' . $from . '","' . $to . '")');

        $pdf = PDF::loadView(
            'report.purchaseRequisitionSummery',
            ['preqs' => $preqs, 'reqData' => $reqData],
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
        return $pdf->stream('report.purchaseRequisitionSummery');
    }
}
