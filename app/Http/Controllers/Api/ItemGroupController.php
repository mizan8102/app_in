<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemGroupController extends Controller
{
    public function index()
    {
        return DB::table('var_item_group')->select('itm_grp_id as id','itm_grp_name as name')->get();
    }
}
