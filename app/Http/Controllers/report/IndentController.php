<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use DB;

class IndentController extends Controller
{
    public function indentReport(Request $request){
        $indents = DB::select('CALL Report_B_01A_IndentReport("'.$request->no.'")');
        $pdf = PDF::loadView('report.indentReport',['indents'=>$indents],
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

        return $pdf->stream('indentRepor.pdf'); 
    }
    public function indentSummery(){
        $pdf = PDF::loadView('report.indentSummery');
        return $pdf->stream('indentSummery.pdf'); 
    }
    public function indentReportTwoPdf(Request $request){

        $indents = DB::select('CALL GetConsumtionDetailByOrderMaster("'.$request->orderMasterId.'")');
        //dd($indents);
            $groups = collect($indents)->groupBy('masterGroupId');
            $pdf = PDF::loadView( 'report.indentReportTwoPdf',  ['indents' => $indents, 'groups' => $groups],
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
            return $pdf->stream('report.indentReportTwoPdf.pdf');
    }

    public function indentReportPdf(Request $request){
        $indents = DB::select('CALL Report_B_01A_IndentReport("'.$request->indentNo.'")');
        //  dd($indents);
        $pdf = PDF::loadView('report.indentReportPdf',compact('indents'));
        return $pdf->stream('report.indentReportPdf.pdf'); 
    }
}
