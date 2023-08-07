<?php

namespace App\Http\Controllers;

use App\Models\CsCompanyStoreLocation;
use App\Models\SubGroup;
use App\Models\ProductType;
use App\Models\ItemSubGroup;
use App\Models\ProductGroup;
use Illuminate\Http\Request;
use App\Models\ItemMasterGroup;
use App\Models\ProductCatagory;

class ProductResourceControllerToGet extends Controller
{
    public function category(){
        $category = request('category')
                ? ProductCatagory::with('productTypes','productTypes.itemMasterGroups','productTypes.itemMasterGroups.productGroups','productTypes.itemMasterGroups.productGroups.sub_group','productTypes.itemMasterGroups.productGroups.sub_group.var_item_info')->find(request('category'))
                : ProductCatagory::with('productTypes','productTypes.itemMasterGroups','productTypes.itemMasterGroups.productGroups','productTypes.itemMasterGroups.productGroups.sub_group','productTypes.itemMasterGroups.productGroups.sub_group.var_item_info')->get();

            return response()->json([
                'success'=>'true',
                'message'=>'all category retrived successfully',
                'data'=>$category,
                'status'=>200,
            ]);
    }
    public function type(){
        $type = request('type')
                ? ProductType::with('itemMasterGroups','itemMasterGroups.productGroups','itemMasterGroups.productGroups.sub_group','itemMasterGroups.productGroups.sub_group.var_item_info')->find(request('type'))
                : ProductType::with('itemMasterGroups','itemMasterGroups.productGroups','itemMasterGroups.productGroups.sub_group','itemMasterGroups.productGroups.sub_group.var_item_info')->get();

            return response()->json([
                'success'=>'true',
                'message'=>'all type retrived successfully',
                'data'=>$type,
                'status'=>200,
            ]);
    }
    public function masterGroups(){
        $masterGroup = (request('masterGroups') ? ItemMasterGroup::with('productGroups', 'productGroups.sub_group', 'productGroups.sub_group.var_item_info')->find(request('masterGroup'))
            : request('value')) ? ItemMasterGroup::where('prod_type_id',3)->get() : ItemMasterGroup::with('productGroups', 'productGroups.sub_group', 'productGroups.sub_group.var_item_info')->get();

            return response()->json([
                'success'=>'true',
                'message'=>'all master groups retrived successfully',
                'data'=>$masterGroup,
                'status'=>200,
            ]);
    }
    public function groups(){
        $groups = request('groups')
                ? ProductGroup::with('sub_group','sub_group.var_item_info')->find(request('groups'))
                : ProductGroup::with('sub_group','sub_group.var_item_info')->get();

            return response()->json([
                'success'=>'true',
                'message'=>'all groups retrived successfully',
                'data'=>$groups,
                'status'=>200,
            ]);
    }
    public function subGroups(){
        $subGroups = request('subGroups')
                ? SubGroup::with('var_item_info')->find(request('subGroups'))
                : SubGroup::with('var_item_info')->get();

            return response()->json([
                'success'=>'true',
                'message'=>'all sub groups retrived successfully',
                'data'=>$subGroups,
                'status'=>200,
            ]);
    }
    public function store(){
        $store = request('store')
                ? CsCompanyStoreLocation::find(request('store'))
                : CsCompanyStoreLocation::get();
            return response()->json([
                'success'=>'true',
                'message'=>'all sub groups retrived successfully',
                'data'=>$store,
                'status'=>200,
            ]);
    }
}
