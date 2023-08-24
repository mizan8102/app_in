<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
class DailySellsSummaryController extends Controller
{
    public function dailySellsSummary(Request $request)
    {
        // $pdf = PDF::loadView('daily_sells_summary');

        // return $pdf->stream('daily_sells_summary.pdf');
        // $dailyID = 1;

        // $dailies = DB::select('CALL Report_D_01A_DailySellCashReceiveSummary("'.$request->cashReceiveDate.'")');
        // $pdf = PDF::loadView('daily_sells_summary', compact('dailies'));

        // return $pdf->stream('daily_sells_summary.pdf');

        $dailies = DB::select('CALL Report_D_01A_DailySellCashReceiveSummary("'.$request->cashReceiveDate.'")');
        $pdf = PDF::loadView('daily_sells_summary', compact('dailies'));

        return $pdf->stream('daily_sells_summary.pdf');

    }

    public function dailySell(Request $request)
    {
        if(isset($request->cashReceiveDate)){
            $dailies=DB::select('CALL Report_D_01A_DailySellCashReceiveSummary("'.$request->cashReceiveDate.'")');
            return view('daily_sell_summary_preview',compact('dailies'));
        }else{
            $dailies=[];
            return view('daily_sell_summary_preview',compact('dailies'));
        }
    }

    public function summaryCashReceive()
    {
        $pdf = PDF::loadView('daily_sells_summary_cash');

        return $pdf->stream('daily_sells_summary_cash.pdf');
    }


    public function storeWiseItem()
    {
        {
            $pdf = PDF::loadView('daily_cash_store_wise_item');

            return $pdf->stream('daily_cash_store_wise_item.pdf');
        }
    }

    public function storeWiseUser()
    {
        {
            $pdf = PDF::loadView('daily_cash_store_wise_user');

            return $pdf->stream('daily_cash_store_wise_iuser.pdf');
        }
    }

    public function userWiseSells( Request $request)
    {
        {
            $userIds=DB::select('CALL getUserId');
            $users = DB::select('CALL userWiseSellsReport("'.$request->issueDate.'","'.$request->userId.'")');
            $pdf = PDF::loadView('user_wise_sells', ['users' => $users, 'userIds' => $userIds ],
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

        return $pdf->stream('user_wise_sells.pdf');
        }
    }
    public function userWise(Request $request)
        {
            $userIds=DB::select('CALL getUserId');
            if(isset($request->issueDate) && isset($request->userId)){
                $users = DB::select('CALL userWiseSellsReport("'.$request->issueDate.'","'.$request->userId.'")');
                //dd($users);
                return view('user_wise_sells_preview',compact('users', 'userIds'));
            }else{
                $users=[];
                return view('user_wise_sells_preview',compact('users', 'userIds'));
            }
        }

        public function storeWise(Request $request)
        {
            $masters=DB::select('CALL getProductMasterGroup');
            $storeIds=DB::select('CALL getStoreList');
            //dd($resIds);
                 if ($request->masterGroupId == ''){
                  return view('store_wise_item_list_preview' , compact('masters'));
                 }
                 $stores = DB::select('CALL storeWiseItemList("'.$request->masterGroupId.'","'.$request->storeId.'")');
                 //dd($orders);
                 $groups = collect($stores)->groupBy('groupId');
                 $pdf = PDF::loadView('store_wise_item_list', ['masters' => $masters, 'storeIds' => $storeIds, 'stores' => $stores, 'groups' => $groups],
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
                 return $pdf->stream('store_wise_item_list.pdf');

        }


        public function store(Request $request)
        {
                $masters=DB::select('CALL getProductMasterGroup');
                $storeIds=DB::select('CALL getStoreList');
            if(isset($request->masterGroupId) && isset($request->storeId)){
                $stores = DB::select('CALL storeWiseItemList("'.$request->masterGroupId.'","'.$request->storeId.'")');
                $groups = collect($stores)->groupBy('groupId');
                return view('store_wise_item_list_preview',compact('masters','storeIds','stores', 'groups'));
            }else{
                $stores=[];
                return view('store_wise_item_list_preview',compact('masters','storeIds','stores'));
            }
        }

        public function dailyMismatchSales(Request $request)
        {
            $data=request('from','');
            
            $mismatches = DB::select('CALL GetDailyMismatchSalesStoreWiseReport("'.date('Y-m-d',strtotime($data)).'")');
            $pdf = PDF::loadView('report.daily_mismatch_store', ['mismatches' => $mismatches,'date'=>date('Y-m-d',strtotime($data))],
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

            return $pdf->stream('daily_mismatch_store.pdf');
        }

        public function dailyMismatch(Request $request)
        {
            if(isset($request->issueDate)){
                $mismatches = DB::select('CALL GetDailyMismatchSalesStoreWiseReport("'.$request->issueDate.'")');
                // dd($mismatches);
                return view('daily_mismatch_store_preview',compact('mismatches'));
            }else{
                $mismatches=[];
                return view('daily_mismatch_store_preview',compact('mismatches'));
            }
        }
        public function pdf(Request $request)
        {
            $masters=DB::select('CALL ReportC_01C_CostionConsumption("'.$request->groupId.'")');
            $groups = collect($masters)->groupBy('groupId');
                 $pdf = PDF::loadView('report.costingPdf', ['masters' => $masters, 'groups' => $groups],
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
                 return $pdf->stream('report.costingPdf.pdf');

        }
        public function consumptionPdf(Request $request){
            $storeID=request('storeId',Null);
            $masterId=request('masterGroupId',Null);
          
                 $stores = DB::select('CALL Report_C_01C_ListofFoodItemwithoutConsumption("'.$request->storeId.'","'.$request->masterGroupId.'")');
                 $groups = collect($stores)->groupBy('groupId');
                 $pdf = PDF::loadView( 'report.consumption_item',  ['stores' => $stores, 'groups' => $groups],
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
                 return $pdf->stream('store_wise_item_list.pdf');
        }
}