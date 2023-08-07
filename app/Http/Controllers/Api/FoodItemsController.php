<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VarItemInfo;
use Illuminate\Support\Facades\Auth;

class FoodItemsController extends Controller
{
    public function getFoodMenus(Request $request)
    {
        return VarItemInfo::select(
            'id as item_information_id',
            'company_id',
            'itm_sub_grp_id',
            'prod_type_id',
            'itm_code',
        )
            ->whereHas('item_detail', function ($query) {
                // $query->where('store_id', Auth::user()->store_id);
            })
            ->with([
                'sub_group' => function ($query) {
                    $query->select('id as itm_sub_grp_id', 'itm_grp_id', 'itm_sub_grp_des', 'itm_sub_grp_des');
                },
            ])
            ->groupBy('itm_sub_grp_id')
            ->get();
    }
    public function getFoodItemsParam(Request $request)
    {
        $items = VarItemInfo::select(
            'id as item_information_id',
            'company_id',
            'itm_sub_grp_id',
            'prod_type_id',
            'itm_code',
        )
            ->with([
                'sub_group' => function ($query) {
                    $query->select('id as itm_sub_grp_id', 'itm_grp_id', 'itm_sub_grp_des', 'itm_sub_grp_des');
                },
                'item_detail' => function ($query) {
                    $query->select('id as item_details_id', 'item_information_id', 'description', 'description_bn', 'item_image', 'price');
                }
            ])
            ->whereHas('item_detail', function ($query) use ($request) { 
                    $query->where(function($q1) use ($request) {
                        $q1->where(function($q2) use ($request){
                                $q2->where('description', 'like', '%' . $request->search . '%')
                                    ->orWhere('description_bn', 'like', '%' . $request->search . '%');
                            });
                            // ->where('store_id', '=', Auth::user()->store_id);
                        });
            })            
            ->when($request->get('cat') != 'all', function ($query) use ($request) {
                $query->where('itm_sub_grp_id', $request->cat);
            })
            ->get();
        return ['items' => $items, 'image_path' => asset('/backend/images/menu_images/')];
    }

}
