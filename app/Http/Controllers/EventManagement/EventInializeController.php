<?php

namespace App\Http\Controllers\EventManagement;

use App\Http\Controllers\Controller;
use App\Models\CustomerDetails;
use App\Models\IOCPriceDeclaration;
use App\Models\OrderStatus;
use App\Models\ProgramSession;
use App\Models\ProgramType;
use App\Models\VarItemInfo;
use DB;
class EventInializeController extends Controller
{
    public function __invoke(){
        $hall_room=DB::select("CALL event_hall_room()");
        $pro_type=ProgramType::where('is_active',1)->get();
        $customer=CustomerDetails::select('cs_customer_details.id',
        'customer_name','contact_person',
        'phone')->leftJoin('cs_customer_contact_info',
        'cs_customer_contact_info.customer_id','cs_customer_details.id')
        ->where('cs_customer_details.is_active',1)->get();

        $items=IOCPriceDeclaration::join('var_item_info','tran01a_ioc_price_declaration.item_info_id','var_item_info.id')
        ->leftJoin('var_item_mapping_bin_prodtype','var_item_mapping_bin_prodtype.item_info_id','var_item_info.id')
        ->leftJoin('5m_sv_uom', 'var_item_info.uom_id', '=', '5m_sv_uom.id')
        ->leftJoin('var_item_sub_group','var_item_sub_group.id','=','var_item_info.itm_sub_grp_id')
        ->leftJoin('var_item_group', 'var_item_group.id', 'var_item_sub_group.itm_grp_id')
        ->leftJoin('var_item_master_group', 'var_item_group.itm_mstr_grp_id', 'var_item_master_group.id')
        ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','var_item_master_group.prod_type_id')
        ->select(
            'var_item_info.id',
            'var_item_info.itm_sub_grp_id',
            'var_item_info.prod_type_id',
            'var_item_master_group.id as master_group_id',
            'var_item_info.current_rate',
            'var_item_info.display_itm_name',
            '5m_sv_uom.id as uom_id',
            '5m_sv_uom.uom_short_code'
        )
        ->where('var_item_mapping_bin_prodtype.store_id',18)
        ->where('var_item_info.is_active',1)
        ->where('var_item_info.prod_type_id', 3)
        ->groupBy('tran01a_ioc_price_declaration.item_info_id')
        ->get();
        $collection = collect($items);

        // program menu
        $menuitem = $collection->where('prod_type_id', 3);
        $result = [];
        $m = 0;
        foreach ($menuitem as $item => $key) {
            $result[$m]['id'] = $key->id;
            $result[$m]['prod_type_id'] = $key->prod_type_id;
            $result[$m]['current_rate'] = $key->current_rate;
            $result[$m]['display_itm_name'] = $key->display_itm_name;
            $result[$m]['uom_id'] = $key->uom_id;
            $result[$m]['uom_short_code'] = $key->uom_short_code;
            $m++;
        }


        $itemsRide=VarItemInfo::leftJoin('var_item_mapping_bin_prodtype','var_item_mapping_bin_prodtype.item_info_id','var_item_info.id')
        ->leftJoin('5m_sv_uom', 'var_item_info.uom_id', '=', '5m_sv_uom.id')
        ->leftJoin('var_item_sub_group','var_item_sub_group.id','=','var_item_info.itm_sub_grp_id')
        ->leftJoin('var_item_group', 'var_item_group.id', 'var_item_sub_group.itm_grp_id')
        ->leftJoin('var_item_master_group', 'var_item_group.itm_mstr_grp_id', 'var_item_master_group.id')
        ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','var_item_master_group.prod_type_id')
        ->select(
            'var_item_info.id',
            'var_item_info.itm_sub_grp_id',
            'var_item_info.prod_type_id',
            'var_item_master_group.id as master_group_id',
            'var_item_info.current_rate',
            'var_item_info.display_itm_name',
            '5m_sv_uom.id as uom_id',
            '5m_sv_uom.uom_short_code'
        )
        ->where('var_item_mapping_bin_prodtype.store_id',18)
        ->where('var_item_info.is_active',1)
        // ->where('var_item_info.prod_type_id', 3)
        ->where('var_item_master_group.id', 15)
      
        ->get();
        // ride
        // $ride = $collection->where('master_group_id', 15);
        $rideResult = [];
        $k = 0;
        foreach ($itemsRide as $item => $key) {
            $rideResult[$k]['id'] = $key->id;
            $rideResult[$k]['prod_type_id'] = $key->prod_type_id;
            $rideResult[$k]['current_rate'] = $key->current_rate;
            $rideResult[$k]['display_itm_name'] = $key->display_itm_name;
            $rideResult[$k]['uom_id'] = $key->uom_id;
            $rideResult[$k]['uom_short_code'] = $key->uom_short_code;
            $k++;
        }


        $itemsService=VarItemInfo::leftJoin('var_item_mapping_bin_prodtype','var_item_mapping_bin_prodtype.item_info_id','var_item_info.id')
        ->leftJoin('5m_sv_uom', 'var_item_info.uom_id', '=', '5m_sv_uom.id')
        ->leftJoin('var_item_sub_group','var_item_sub_group.id','=','var_item_info.itm_sub_grp_id')
        ->leftJoin('var_item_group', 'var_item_group.id', 'var_item_sub_group.itm_grp_id')
        ->leftJoin('var_item_master_group', 'var_item_group.itm_mstr_grp_id', 'var_item_master_group.id')
        ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','var_item_master_group.prod_type_id')
        ->select(
            'var_item_info.id',
            'var_item_info.itm_sub_grp_id',
            'var_item_info.prod_type_id',
            'var_item_master_group.id as master_group_id',
            'var_item_info.current_rate',
            'var_item_info.display_itm_name',
            '5m_sv_uom.id as uom_id',
            '5m_sv_uom.uom_short_code'
        )
        ->where('var_item_mapping_bin_prodtype.store_id',18)
        ->where('var_item_info.is_active',1)
        // ->where('var_item_info.prod_type_id', 3)
        ->where('var_item_master_group.id', 213)
      
        ->get();
        // services
        // $service = $collection->where('master_group_id', 213);
        $serviceResult = [];
        $kk = 0;
        foreach ($itemsService as $item => $key) {
            $serviceResult[$kk]['id'] = $key->id;
            $serviceResult[$kk]['prod_type_id'] = $key->prod_type_id;
            $serviceResult[$kk]['current_rate'] = $key->current_rate;
            $serviceResult[$kk]['display_itm_name'] = $key->display_itm_name;
            $serviceResult[$kk]['uom_id'] = $key->uom_id;
            $serviceResult[$kk]['uom_short_code'] = $key->uom_short_code;
            $kk++;
        }
      
        $program_session=ProgramSession::all();

        $orderStatus=OrderStatus::all();
        
        return response()->json([
            'hall_rooms'        => $hall_room,
            'pro_type'          => $pro_type,
            'menu'              => $result,
            'ride'              => $rideResult,
            'serviece'          => $serviceResult,
            'customers'         => $customer,
            'program_session'   => $program_session,
            'order_Status'      => $orderStatus,
            // 'items' => $items
        ]);

    }
}
