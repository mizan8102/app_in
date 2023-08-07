<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IntemInfoController extends Controller
{
    public function index(Request $request)
    {
        return DB::table('var_item_info')
            ->leftJoin('5m_sv_uom as uom', 'var_item_info.uom_id', '=', 'uom.uom_id')
            ->where('var_item_info.company_id',Auth::user()->company_id)
            ->select(
                'var_item_info.item_information_id as id',
                'var_item_info.display_itm_name as item_name',
                'var_item_info.itm_sub_grp_id',
                'var_item_info.prod_type_id',
                'uom.uom_short_code as uom_code'
            )
            ->get();
    }
}
