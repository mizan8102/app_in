<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use App\Models\IssueChild;
use App\Models\IssueMaster;
use Illuminate\Support\Facades\DB;
use PDF;

class CashReceiveReportController extends Controller
{
    public  function  dailySellCashReceive(){

        $deposite_date =date('Y-m-d',strtotime( request('deposite_date',
        date('Y-m-d'))));
        $dataArr = DB::select('CALL Report_D_01A_DailySellCashReceiveSummary("'.$deposite_date.'")');
        $pdf = PDF::loadView('report.report_d-01A_daily_Sell_cash_receive_summary',['data'=>$dataArr,'deposit_date'=>$deposite_date],
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

                'title'                => 'RM Item List',
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
        return $pdf->stream('report_d-01A_daily_Sell_cash_receive_summary.pdf');
    }

    public function daily_sells_summary(){
        $date=date('Y-m-d',strtotime(request('date',date('Y-m-d'))) );
        // $data= DailySalesSummery::where('issue_date',date('Y-m-d',strtotime($date)))->orWhereNull('issue_date')->get();
        // $data=DB::select("SET @p0='2023-05-21'; CALL `GetDailyStoreWiseSellsAndCashReceive`(@p0);")
        $entry_ticket_store_id=10;

        $night_ticket=IssueChild::leftJoin('trns03a_issue_master','trns03b_issue_child.issue_master_id','trns03a_issue_master.id')
        ->whereDate('sales_invoice_date',$date)->whereTime('sales_invoice_date', '>', '18:00:00')
        ->where('store_id',$entry_ticket_store_id)->sum('issue_qty');
        $day_ticket=IssueChild::leftJoin('trns03a_issue_master','trns03b_issue_child.issue_master_id','trns03a_issue_master.id')
        ->whereDate('sales_invoice_date',$date)->whereTime('sales_invoice_date', '<', '18:00:00')
        ->where('store_id',$entry_ticket_store_id)->sum('issue_qty');


        $data = DB::select('CALL GetDailyStoreWiseSellsAndCashReceive("'.$date.'")');
        $pdf = PDF::loadView('report.daily_sells_summary_cash', ['data' => $data,'day_ticket'=>$day_ticket,'night_ticket'=>$night_ticket,'date'=>$date],[],
            [
                'title' => 'Certificate',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
        return $pdf->stream('daily_sells_summary_cash.pdf');
    }
}
