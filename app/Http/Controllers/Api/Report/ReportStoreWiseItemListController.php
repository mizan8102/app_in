<?php

namespace App\Http\Controllers\Api\Report;

use App\Http\Controllers\Controller;
use App\Models\HouseKeeping\VarItemMappingBinProdtype;
use App\Models\ItemMasterGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ReportStoreWiseItemListController extends Controller
{

    public function store_wise_item_list(Request $request){
        $storeID=request('storeId',Null);
        $producttype=request('productType',Null);
        $masterId=request('masterGroupId',Null);
      
             $stores = DB::select('CALL GetItemMappingByStoreAndMasterGroup("'.$request->storeId.'","'.$request->productType.'","'.$request->masterGroupId.'")');
             $groups = collect($stores)->groupBy('groupId');
             $pdf = PDF::loadView( 'report.storeWiseItemList',  ['stores' => $stores, 'groups' => $groups],
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
    public function init_store_item_mapping(){
        $store=VarItemMappingBinProdtype::select('var_item_mapping_bin_prodtype.store_id as id','sl_name as no')
        ->leftJoin('cs_company_store_location','cs_company_store_location.id','var_item_mapping_bin_prodtype.store_id')
        ->where('var_item_mapping_bin_prodtype.is_active',1)->groupBy('var_item_mapping_bin_prodtype.store_id')
        ->get();
        $masterGroup=ItemMasterGroup::select('id','itm_mstr_grp_name as no')->where('is_active',1)->get();
        return response()->json([
            'store'=> $store,
            'master_group' => $masterGroup
        ]);
    }
    public function index(){
        $stores = DB::select('CALL getStoreList()');
        $masterGroup = DB::select('CALL ItemMasterGroup()');
        return response()->json([
            'stores'=>$stores,
            'masterGroup'=>$masterGroup,
        ]);
    }
    public function getById(){
        $store_id = request()->input('storeId');
        $master_group_id=request()->input('masterGroupId');
        return DB::select('CALL storeWiseItemList("' . $store_id . '","' . $store_id . '")');
    }
    public function getByIdPDF(){
        // return "hello";
        $store_id = request()->input('storeId');
        $items =
            DB::select('CALL storeWiseItemList(4,2)');
        $pdf = PDF::loadView(
            'report.storeWiseItemList',
            ['items' => $items],
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
                'title'                => 'Store Wise Item Report',
                'author'               => 'Zit Solution LTD.',
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

        return $pdf->stream('report.storeWiseItemList');
    }
}
