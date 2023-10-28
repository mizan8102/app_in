<?php

namespace App\Http\Controllers\report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use DB;
class IssueController extends Controller
{
    public function issueDetailsPdf(Request $request){
        $issues=DB::select('CALL Report_C_04A_IssueDetails("'.$request->no.'")');
        // dd($issues);
        $pdf = PDF::loadView('report.issueDetailsPdf',['issues'=>$issues],
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
        return $pdf->stream('report.issueDetailsPdf.pdf');
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

    




    // Issue Summary
    public function issueSummeryPdf(Request $request){

        $from =  Date('Y-m-d',strtotime($request->from));
        $to =  Date('Y-m-d',strtotime($request->to));
        
        $issues=DB::select('CALL Report_C_04B_IssueSummary("'.$from.'","'.$to.'",
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







    public function issueReturnSummaryPdf(Request $request){
        $from = date('Y-m-d',strtotime($request->from));
        $to = date('Y-m-d',strtotime($request->to));
        $returns = DB::select('CALL Report_C_05C_IssueReturnSummary("'.$from.'","'.$to.'")');
        $pdf = PDF::loadView('report.issue_return_summary', ['returns' => $returns],
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
        return $pdf->stream('report.issue_return_summary.pdf');
    }




    // 26-10-23 C05B Issue Return by program
    public function issueReturnByprogramPdf(Request $request){

        $issues = DB::select('CALL Report_C_05B_IssueReturnByProgram("'.$request->no.'")');
        $pdf = PDF::loadView('report.issue_return_program', ['issues' => $issues],
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
        return $pdf->stream('report.issue_return_program.pdf');
    }
}
    

