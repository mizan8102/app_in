<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use DB;
class IssueController extends Controller
{
    public function issueDetails(){
        $pdf = PDF::loadView('report.issueDetails');
        return $pdf->stream('issueDetails.pdf'); 
    }
    public function issueSummery(){
        $pdf = PDF::loadView('report.issueSummery');
        return $pdf->stream('issueSummery.pdf'); 
    }
    public function issueReturn(Request $request){
        
        $issueReturns=DB::select('CALL Report_C_05A_IssueReturn("'.$request->no.'")');
        $pdf = PDF::loadView('report.issueReturn',['issueReturns'=>$issueReturns],
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
        return $pdf->stream('report.issueReturn.pdf');
    }

    public function issueDetailsPdf(Request $request){
       

        $issues=DB::select('CALL Report_C_04A_IssueDetails("'.date('Y-m-d',strtotime($request->from)).'","'.date('Y-m-d',strtotime($request->to)).'", ("'.$request->no.'")');
        dd($receives);
        $pdf = PDF::loadView('report.receiveSummaryPdf',['receives'=>$receives, 'from' =>$request->from, 'to' => $request->to, 'no' => $request->no],
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
        return $pdf->stream('report.issueDetailsPdf.pdf');
    }

    public function issueSummeryPdf(Request $request){
        $issues=DB::select('CALL Report_C_04B_IssueSummary("'.$request->fromDate.'","'.$request->toDate.'",
        "'.$request->no.'")');
        // dd($issues);
        $pdf = PDF::loadView('report.issueSummaryPdf',['issues'=>$issues],
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
        return $pdf->stream('report.issueSummaryPdf.pdf');
    }
}
    

