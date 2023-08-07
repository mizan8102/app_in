<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use PDF;

class ProductTypeWiseItemReportController extends Controller
{
    public function RMListMasterGroupWise(Request $request)
    {
        ini_set("pcre.backtrack_limit", "10000000");
        $catId = $request->cat_id;
        $prodTypeId = $request->prod_type_id;
        $mstrGroupId =  $request->mstr_group_id;
        $groupId = $request->group_id;
        $subGroupId = $request->sub_group_id;
        $data = DB::select('CALL Report_ProductTypeItemList("'.$catId.'","'.$prodTypeId.'","'.$mstrGroupId.'","'.$groupId.'","'.$subGroupId.'")');
        $dataArr = collect($data)->groupBy('masterGroupName')->sortBy('GroupName')->sortBy('subGroupName')->sortBy('display_itm_name_bn');
        $prodType = DB::table('5f_sv_product_type')->where('id',$prodTypeId)->first();
   

        $pdf = PDF::loadView('report.product_type_wise_item_list',['data'=>$dataArr,'prodType'=>$prodType],
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
               
            ],
           
        );
        return $pdf->stream('report.product_type_wise_item_list.pdf');
    }

    public function ItemListByMasterGroup(Request $request)
    {
        ini_set("pcre.backtrack_limit", "9000000");

        $pt = $request->pt = 2;
        $data = DB::select('CALL Report_ProductTypeItemList("'.$pt.'")');
        $dataArr = collect($data)->groupBy('masterGroupName')->sortBy('masterGroupName');
        $prodType = DB::table('5f_sv_product_type')->where('id',$pt)->first();
        $pdf = PDF::loadView('item_list_by_master_group',['data'=>$dataArr,'prodType'=>$prodType],
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
        return $pdf->stream('report.item_list_by_master_group.pdf');
    }

}
