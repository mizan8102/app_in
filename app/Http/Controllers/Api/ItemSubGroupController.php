<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemSubGroupController extends Controller
{
    public function index(Request $request)
    {
        return DB::table('var_item_sub_group')
            // ->where('itm_grp_id',$request->item_group_id)
            ->select(
                'itm_sub_grp_id as id',
                'itm_grp_id',
                'itm_sub_grp_des as name'
            )->get();
    }
}
