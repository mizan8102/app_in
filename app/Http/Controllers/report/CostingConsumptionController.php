<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use DB;
class CostingConsumptionController extends Controller
{
    public function costingConsumption()
    {
        $pdf = PDF::loadView('report.costing_consumption');
        
        return $pdf->stream('costing_consumption.pdf');
    }
    public function costingConsumptionPDF(Request $request){
        $iocID = $request->no;
        $type = 'IocDetail';
        $dataArr = DB::select('CALL GetPriceDeclarationDetailsIOC("'.$iocID.'")');
        $data['iocMaster'] = $dataArr[0];
        $data['iocDetail'] = collect($dataArr)->where('itemType','RM')->values();
        $data['iocInputService'] = collect($dataArr)->where('itemType','InputService')->values();
        $data['iocVas'] = collect($dataArr)->where('itemType','VAS')->values();
        // return $data;
        $pdf = PDF::loadView('report.ioc_report_pdf',['data'=>$data],
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
                'custom_font_data' 	   => [],
                'auto_language_detection'  => false,
                'temp_dir'               => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
                'pdfa' 			=> false,
                'pdfaauto' 		=> false,
            ]
        );
        return $pdf->stream('ioc_report_pdf.pdf');
    }
}
