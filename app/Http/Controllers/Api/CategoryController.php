<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        return DB::table('5h_sv_product_category')->select('prod_cat_id as id','prod_cat_name as name')->get();
    }
}
