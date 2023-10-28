<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use PDF;
class CostingConsumptionSummaryController extends Controller
{
    public function costingConsumptionSummary()
    {
        $pdf = PDF::loadView('report.costing_consumption_summary');
        
        return $pdf->stream('costing_consumption_summary.pdf');
    }

    public function costingConsumptionSummaryPdf(Request $request){
        $orders = DB::select('CALL Report_C_01B_CostingConsumptionSummary(?)', [$request->no]);
        $pdf = PDF::loadView('report.costing_consumption_summary_pdf', ['orders' => $orders],
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

            return $pdf->stream('report.costing_consumption_summary_pdf.pdf');
        }
}
