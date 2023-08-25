<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use App\Models\ItemMasterModel;
use App\Models\OrderMaster;
use Illuminate\Http\Request;
use PDF;
class ProgramIndentController extends Controller
{
    public function indentReportTwoPdf(Request $request){
        $id = $request->id;
        $orderMaster = OrderMaster::with(['orderChild'=>function($query){
            $query->with('item_info.sv_uom')
            ->leftJoin('var_item_info','var_item_info.id','trns00h_order_child.item_info_id')
            ->leftJoin('5m_sv_uom','5m_sv_uom.id','var_item_info.uom_id')
            ->where('var_item_info.prod_type_id',3);
        },'hallRoom'])
        ->find($id);
            $masterGroupWiseData=ItemMasterModel::leftJoin('trns00b_indent_child','trns00b_indent_child.indent_master_id','trns00a_indent_master.id')
            ->leftJoin('var_item_info','trns00b_indent_child.item_info_id','var_item_info.id')
            ->where('order_master_id',$id)
            ->get();

            $dd=collect($masterGroupWiseData)->groupBy('indent_number')->values();     
            $pdf = PDF::loadView( 'report.program_indent_report',  ['indents' => $dd, 'orderMaster' => $orderMaster],
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
            return $pdf->stream('indentReportTwoPdf.pdf');
    }

}
