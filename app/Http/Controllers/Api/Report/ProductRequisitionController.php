<?php

namespace App\Http\Controllers\Api\Report;

use App\Http\Controllers\Controller;
use App\Models\IndentChildren;
use App\Models\ItemChildModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ProductRequisitionController extends Controller
{
    public function getProductRequisitionNo(Request $request)
    {
        $CloseStatus = 0;
        $IsProductReq = 1;
        $data = DB::select('CALL GetIndentID("' . $CloseStatus . '","' . $IsProductReq . '")');
        $indents = [];
        foreach ($data as $i => $item) {
            $indents[$i]['id'] = $item->indentNo;
            $indents[$i]['no'] = $item->indentNo;
        }
        return $indents;
    }
    public function getProductRequisitionByNo(Request $request)
    {
        return $items = DB::select('CALL Report_B_02A_ProductRequisition("' . $request->no . '")');
    }
    public function getProductRequisitionByNoPDF(Request $request)
    {
        $req_no=$request->no;
        $indents = DB::select('CALL Report_B_02A_ProductRequisition("' . $request->no . '")');
        $inden_child = IndentChildren::select('trns00a_indent_master.indent_number','trns00a_indent_master.indent_date',
        'var_item_master_group.itm_mstr_grp_name','users.name',
        'demand_store.sl_name as from_store','toStore.sl_name as to_store')
        ->leftJoin('trns00a_indent_master','trns00a_indent_master.id','trns00c_indent_ChildIndent.indent_master_id')
        ->leftJoin('trns00b_indent_child','trns00b_indent_child.id' ,'trns00c_indent_ChildIndent.indent_child_id')
        ->leftJoin('trns00a_indent_master as InDent','InDent.id','trns00b_indent_child.indent_master_id')
        ->leftJoin('var_item_master_group','var_item_master_group.id','trns00a_indent_master.master_group_id')
        ->leftJoin('cs_company_store_location as demand_store','demand_store.id','trns00a_indent_master.demand_store_id')
        ->leftJoin('cs_company_store_location as toStore','toStore.id','trns00a_indent_master.to_store_id')
        ->leftJoin('users','users.id','InDent.submitted_by')
        ->where('InDent.indent_number',$req_no)
        ->groupBy('trns00a_indent_master.indent_number')
        ->get();
        // dd($inden_child);
    
        $pdf = PDF::loadView(
            'report.productRequisition',
            ['indents' => $indents,'indent_no'=>$request->no,'issue_table'=>$inden_child],
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

        return $pdf->stream('productRequisition.pdf');
    }
    public function productRequisitionSummaryByDate(Request $request){
        $from = date('Y-m-d',strtotime($request->from));
        $to = date('Y-m-d',strtotime($request->to));
        return $items=DB::select('CALL Report_B_02B_ProductRequisitionSummary("'.$from.'","'.$to.'")');
    }
    public function productRequisitionSummaryByDatePDF(Request $request){
        $from = date('Y-m-d',strtotime($request->from));
        $to = date('Y-m-d',strtotime($request->to));
        $reqData['from_date'] = date('d-m-Y',strtotime($request->from));
        $reqData['to_date'] = date('d-m-Y',strtotime($request->to));
        $indents = DB::select('CALL Report_B_02B_ProductRequisitionSummary("'.$from.'","'.$to.'")');

        $pdf = PDF::loadView('report.requisitionSummery',['indents'=>$indents,'reqData'=>$reqData , 'form' => $from, 'to'=> $to],
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
        return $pdf->stream('requisitionSummery.pdf');
    }
}