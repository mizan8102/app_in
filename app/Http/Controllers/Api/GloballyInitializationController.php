<?php

namespace App\Http\Controllers\Api;

use App\Models\SubGroup;
use App\Models\ProductType;
use App\Models\ProductGroup;
use Illuminate\Http\Request;
use App\Models\ItemMasterGroup;
use App\Models\ProductCatagory;
use App\Http\Controllers\Controller;

class GloballyInitializationController extends Controller
{
    public function category()
    {
        $category = request('category')
            ? ProductCatagory::with('productTypes', 'productTypes.itemMasterGroups', 'productTypes.itemMasterGroups.productGroups', 'productTypes.itemMasterGroups.productGroups.sub_group')->find(request('category'))
            : ProductCatagory::with('productTypes', 'productTypes.itemMasterGroups', 'productTypes.itemMasterGroups.productGroups', 'productTypes.itemMasterGroups.productGroups.sub_group')->get();

        return response()->json([
            'success' => 'true',
            'message' => 'all category retrived successfully',
            'data' => $category,
            'status' => 200,
        ]);
    }
    public function type()
    {
        $type = request('type')
            ? ProductType::with('itemMasterGroups', 'itemMasterGroups.productGroups', 'itemMasterGroups.productGroups.sub_group')->find(request('type'))
            : ProductType::with('itemMasterGroups', 'itemMasterGroups.productGroups', 'itemMasterGroups.productGroups.sub_group',)->get();

        return response()->json([
            'success' => 'true',
            'message' => 'all type retrived successfully',
            'data' => $type,
            'status' => 200,
        ]);
    }
    public function masterGroups()
    {
        $masterGroup = request('masterGroups')
            ? ItemMasterGroup::with('productGroups', 'productGroups.sub_group')->find(request('masterGroup'))
            : ItemMasterGroup::with('productGroups', 'productGroups.sub_group')->get();

        return response()->json([
            'success' => 'true',
            'message' => 'all master groups retrived successfully',
            'data' => $masterGroup,
            'status' => 200,
        ]);
    }
    public function groups()
    {
        $groups = request('groups')
            ? ProductGroup::with('sub_group')->find(request('groups'))
            : ProductGroup::with('sub_group')->get();

        return response()->json([
            'success' => 'true',
            'message' => 'all groups retrived successfully',
            'data' => $groups,
            'status' => 200,
        ]);
    }
    public function subGroups()
    {
        $subGroups = request('subGroups')
            ? SubGroup::find(request('subGroups'))
            : SubGroup::get();

        return response()->json([
            'success' => 'true',
            'message' => 'all sub groups retrived successfully',
            'data' => $subGroups,
            'status' => 200,
        ]);
    }
}
