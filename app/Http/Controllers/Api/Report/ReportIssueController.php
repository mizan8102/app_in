<?php

namespace App\Http\Controllers\Api\Report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\IssueMaster;
use PDF;

class ReportIssueController extends Controller
{
    public function getIssueNo()
    {
        $data = DB::select('CALL IssueID()');
        $pos = [];
        foreach ($data as $i => $item) {
            $pos[$i]['id'] = $item->issueId;
            $pos[$i]['no'] = $item->issueNo;
        }
        return $pos;
    }
    public function getIssueDetailsByNo(Request $request)
    {
        return DB::select('CALL Report_C_05A_IssueReturn("' . $request->no . '")');
    }
    public function getIssueDetailsByNoByNoPDF(Request $request)
    {
        $issue =
            DB::select('CALL Report_C_04A_IssueDetails("' . $request->no . '")');
        $pdf = PDF::loadView(
            'report.issueDetails',
            ['issue' => $issue],
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

        return $pdf->stream('report.issueDetails');
    }
    public function getIssueSummaryByDate(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from));
        $to = date('Y-m-d', strtotime($request->to));
        $no = request()->input('no');
        return $issue = IssueMaster::leftJoin('cs_company_store_location', 'cs_company_store_location.id', '=', 'trns03a_issue_master.store_id')
            ->leftJoin('trns00a_indent_master', 'trns00a_indent_master.id', 'trns03a_issue_master.indent_master_id')
            ->leftJoin('var_item_master_group', 'var_item_master_group.id', 'trns00a_indent_master.master_group_id')
            ->select('trns03a_issue_master.*', 'cs_company_store_location.sl_name as sl_name', 'var_item_master_group.itm_mstr_grp_name as itm_mstr_grp_name', 'var_item_master_group.id as master_group_id')
            ->whereBetween('trns03a_issue_master.issue_date', [$from, $to])
            // ->where('master_group_id',$no)
            ->get();
        // return $issue = DB::select('CALL Report_C_04B_IssueSummary("' . $from . '","' . $to .'","' . $no . '")');
    }
    public function getIssueSummaryByDatePDF(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from));
        $to = date('Y-m-d', strtotime($request->to));
        $no = request()->input('no');
        $issue = IssueMaster::leftJoin('cs_company_store_location', 'cs_company_store_location.id', '=', 'trns03a_issue_master.store_id')
            ->leftJoin('trns00a_indent_master', 'trns00a_indent_master.id', 'trns03a_issue_master.indent_master_id')
            ->leftJoin('var_item_master_group', 'var_item_master_group.id', 'trns00a_indent_master.master_group_id')
            ->select('trns03a_issue_master.*', 'cs_company_store_location.sl_name as sl_name', 'var_item_master_group.itm_mstr_grp_name as itm_mstr_grp_name', 'var_item_master_group.id as master_group_id')
            ->whereBetween('trns03a_issue_master.issue_date', [$from, $to])
            ->where('master_group_id', $no)
            ->get();
        $pdf = PDF::loadView(
            'report.issueSummery',
            ['issue' => $issue],
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
        return $pdf->stream('report.issueSummery');
    }
}
