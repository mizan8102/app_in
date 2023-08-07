<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\SvUOM;
use App\Models\Currency;
use App\Models\SubGroup;
use App\Models\ProductType;
use App\Models\VarItemInfo;
use App\Models\ProductGroup;
use Illuminate\Http\Request;
use App\Models\IndentChildren;
use App\Models\ItemChildModel;
use App\Models\ItemMasterGroup;
use App\Models\ItemMasterModel;
use App\Models\ProductCatagory;
use App\Models\IsseIndentMaster;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CsCompanyStoreLocation;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\GeneralIndentStoreRequest;

class GeneralIndentController extends Controller
{
    public $store;

    public function __construct()
    {
        if (auth()->check()) {
            $this->store = CsCompanyStoreLocation::where('id', auth()->user()->store_id)->first();
        }

        $this->middleware(function ($request, $next) {
            $response = $next($request);
            if ($this->store) {
                $response->setContent(json_decode($response->getContent(), true) + ['store' => $this->store]);
            }
            return $response;
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request)
    // {
    //     $catagory = $request->catagory;
    //     $type = $request->type;
    //     $master_group = $request->master_group;
    //     $group = $request->group;
    //     $subgroup = $request->sub_group;
    //     $store=CsCompanyStoreLocation::where('id',auth()->user()->store_id)->first();

    //     if(!empty($catagory) && empty($type) && empty($master_group) && empty($group) && empty($subgroup)){
    //         $pro_catagory = ProductCatagory::find($catagory);
    //         $pro_type = ProductType::where('prod_cat_id',$pro_catagory->id)->get();
    //         $pro_group =[];
    //         $pro_master_group =[];
    //         return response()->json([
    //             'store' => $store,
    //             'pro_catagory' => $pro_catagory,
    //             'pro_type' => $pro_type,
    //             'pro_master_group' => $pro_master_group,
    //             'pro_group' => $pro_group,
    //         ]);
    //     }elseif(!empty($catagory) && !empty($type) && empty($master_group) && empty($group) && empty($subgroup)){
    //         $pro_catagory = ProductCatagory::find($catagory);
    //         $pro_type = ProductType::find($type);
    //         $pro_master_group = ItemMasterGroup::where('prod_type_id',$pro_type->id)->get();
    //         $pro_group =[];
    //         return response()->json([
    //             'store' => $store,
    //             'pro_catagory' => $pro_catagory,
    //             'pro_type' => $pro_type,
    //             'pro_master_group' => $pro_master_group,
    //             'pro_group' => $pro_group,
    //         ]);
    //     }elseif(!empty($catagory) && !empty($type) && !empty($master_group) && empty($group) && empty($subgroup)){
    //         $pro_catagory = ProductCatagory::find($catagory);
    //         $pro_type = ProductType::find($type);
    //         $pro_master_group = ItemMasterGroup::find($master_group);
    //         $pro_group = ProductGroup::where('itm_mstr_grp_id',$pro_master_group->id)->get();
    //         return response()->json([
    //             'store' => $store,
    //             'pro_catagory' => $pro_catagory,
    //             'pro_type' => $pro_type,
    //             'pro_master_group' => $pro_master_group,
    //             'pro_group' => $pro_group,
    //         ]);
    //     }elseif(!empty($catagory) && !empty($type) && !empty($master_group) && !empty($group) && empty($subgroup)){
    //         $pro_catagory = ProductCatagory::find($catagory);
    //         $pro_type = ProductType::find($type);
    //         $pro_master_group = ItemMasterGroup::find($master_group);
    //         $pro_group = ProductGroup::find($group);
    //         $pro_sub_group=SubGroup::where('itm_grp_id', $pro_group->id)->get();
    //         return response()->json([
    //             'store' => $store,
    //             'pro_catagory' => $pro_catagory,
    //             'pro_type' => $pro_type,
    //             'pro_master_group' => $pro_master_group,
    //             'pro_group' => $pro_group,
    //             'pro_sub_group' => $pro_sub_group,
    //         ]);
    //     }
    //     if(!empty($catagory) && !empty($type) && !empty($master_group) && !empty($group) && !empty($subgroup)){
    //         $pro_catagory = ProductCatagory::find($catagory);
    //         $pro_type = ProductType::find($type);
    //         $pro_master_group = ItemMasterGroup::find($master_group);
    //         $pro_group = ProductGroup::find($group);
    //         $sub_group=SubGroup::find($subgroup);
    //         $item=VarItemInfo::leftJoin('5m_sv_uom', '5m_sv_uom.id', 'var_item_info.id')
    //             ->where('itm_sub_grp_id', $sub_group->id)
    //             ->get();
    //             return response()->json([
    //                 'store' => $store,
    //                 'pro_catagory' => $pro_catagory,
    //                 'pro_type' => $pro_type,
    //                 'pro_master_group' => $pro_master_group,
    //                 'pro_group' => $pro_group,
    //                 'pro_sub_group' => $sub_group,
    //                 'item' => $item,
    //             ]);
    //     }else{
    //         $pro_catagory=ProductCatagory::all();
    //         $pro_type=[];
    //         $pro_master_group=[];
    //         $pro_group=[];
    //         $sub_group=[];
    //         $item=[];
    //         return response()->json([
    //             'store' => $store,
    //             'pro_catagory' => $pro_catagory,
    //             'pro_type' => $pro_type,
    //             'pro_master_group' => $pro_master_group,
    //             'pro_group' => $pro_group,
    //             'pro_sub_group' => $sub_group,
    //             'item' => $item,
    //         ]);
    //     }
    // }
    public function index(Request $request)
    {
        $catagory = $request->catagory;
        $type = $request->type;
        $master_group = $request->master_group;
        $group = $request->group;
        $subgroup = $request->sub_group;
        $pro_type = [];
        $pro_master_group = [];
        $pro_group = [];
        $pro_sub_group = [];
        $store = CsCompanyStoreLocation::get();
        $pro_catagory = ProductCatagory::all();
        $item = VarItemInfo::with('sub_group', 'sub_group.product_group')
            ->leftJoin('5m_sv_uom', '5m_sv_uom.id', 'var_item_info.uom_id')
            ->select('var_item_info.*', 'var_item_info.uom_id', '5m_sv_uom.uom_short_code')
            ->get();
        if (!empty($catagory) && empty($type) && empty($master_group) && empty($group) && empty($subgroup)) {
            $pro_type = ProductType::where('prod_cat_id', $catagory)->get();
        } elseif (!empty($catagory) && !empty($type) && empty($master_group) && empty($group) && empty($subgroup)) {
            $pro_type = ProductType::where('prod_cat_id', $catagory)->get();
            $pro_master_group = ItemMasterGroup::where('prod_type_id', $type)->get();
        } elseif (!empty($catagory) && !empty($type) && !empty($master_group) && empty($group) && empty($subgroup)) {
            $pro_type = ProductType::where('prod_cat_id', $catagory)->get();
            $pro_master_group = ItemMasterGroup::where('prod_type_id', $type)->get();
            $pro_group = ProductGroup::where('itm_mstr_grp_id', $master_group)->get();
        } elseif (!empty($catagory) && !empty($type) && !empty($master_group) && !empty($group) && empty($subgroup)) {
            $pro_type = ProductType::where('prod_cat_id', $catagory)->get();
            $pro_master_group = ItemMasterGroup::where('prod_type_id', $type)->get();
            $pro_group = ProductGroup::where('itm_mstr_grp_id', $master_group)->get();
            $pro_sub_group = SubGroup::where('itm_grp_id', $group)->get();
        } elseif (!empty($catagory) && !empty($type) && !empty($master_group) && !empty($group) && !empty($subgroup)) {
            $pro_type = ProductType::where('prod_cat_id', $catagory)->get();
            $pro_master_group = ItemMasterGroup::where('prod_type_id', $type)->get();
            $pro_group = ProductGroup::where('itm_mstr_grp_id', $master_group)->get();
            $pro_sub_group = SubGroup::where('itm_grp_id', $group)->get();
            $item = VarItemInfo::with('sub_group', 'sub_group.product_group')
                ->leftJoin('5m_sv_uom', '5m_sv_uom.id', 'var_item_info.uom_id')
                ->select('var_item_info.*', 'var_item_info.uom_id', '5m_sv_uom.uom_short_code')
                ->get();
        }
        $result = [];
        $i = 0;
        foreach ($item as $itm) {
            $result[$i]['id'] = $itm->id;
            $result[$i]['uom_id'] = VarItemInfo::find($itm->id)->uom_id;
            $result[$i]['uom_short_code'] = SvUOM::find(VarItemInfo::find($itm->id)->uom_id)->uom_short_code;
            $result[$i]['display_itm_name'] = $itm->display_itm_name;
            $group = $itm['sub_group']['product_group'];
            $result[$i]['sub_grp_id'] = $itm['sub_group'];
            $result[$i]['group_id'] = $group;
            $i++;
        }
        $fiscal = fiscalYearAndMonth(now());
        return response()->json([
            'opening_bal_date' => now()->format('Y-m-d'),
            'currency' =>  Currency::orderBy('currency_shortcode')->get(),
            'fiscal_year' => $fiscal['fiscal_year'],
            'vat_month' => $fiscal['vat_month'],
            'store' => $this->store,
            'pro_catagory' => $pro_catagory,
            'pro_type' => $pro_type,
            'pro_master_group' => $pro_master_group,
            'pro_group' => $pro_group,
            'pro_sub_group' => $pro_sub_group,
            'item' => $result,
        ]);
    }

    public function indentNumber()
    {
        $latest = IsseIndentMaster::latest()->select('id')->orderBy('id', 'Desc')->first();
        if (!$latest) {
            return 'IND-0001';
        }
        $string = preg_replace("/[^0-9\.]/", '', $latest->id);
        $eid = 'IND-' . sprintf('%04d', $string + 1);
        return $eid;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'indent_date' => 'required|date',
            'to_store_id' => 'required',
            'remarks' => 'required|string|max:255',
            'item_row.*.item_information_id' => 'required|numeric',
            'item_row.*.indent_qty' => 'required|numeric',
            'item_row.*.uom_id' => 'required|numeric',
            'item_row.*.indent_req_date' => 'required|date',
            'item_row.*.indent_comment' => 'sometimes|string|max:255',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validated->errors()
            ], 422);
        }
        try {
            $branch_id = auth()->user()->branch_id;
            $company_id = auth()->user()->company_id;
            $store_id = auth()->user()->store_id;
            $created_by = $submitted_by = $recommended_by = $approved_by = auth()->user()->id;
            $comID = Auth::user()->company_id;
            $grnNumber = DB::select('CALL getTableID("trns00a_indent_master","' . $comID . '")');
            $indentmaster = [
                'indent_number' =>
                $grnNumber[0]->masterID,
                'program_master_id' => 0,
                'indent_date' => $request['indent_date'],
                'company_id' => $company_id,
                'branch_id' => $branch_id,
                'demand_store_id' => $store_id,
                'to_store_id' => $request['to_store_id'],
                'remarks' => $request['remarks'],
                'issue_status' => 0,
                'close_status' => 0,
                'recommended_by' => $recommended_by,
                'approved_by' => $approved_by,
                'created_by' => $created_by,
                'updated_by' => $created_by,
            ];
            DB::beginTransaction();
            $indentStoredMaster = ItemMasterModel::create($indentmaster);
            foreach ($request->item_row as $item) {
                $uom_short_code = SvUOM::find($item['uom_id']);
                $indentChild = [
                    'indent_master_id' => $indentStoredMaster->id,
                    'item_information_id' => $request['item_information_id'],
                    'uom_id' => $item['uom_id'],
                    'uom_short_code' => $uom_short_code,
                    'indent_quantity' => $item['indent_qty'],
                    'remarks' => $item['indent_comment'],
                    'required_date' => $item['indent_req_date'],
                    'remarks_bn' => $item['indent_comment'],
                    'created_by' => $created_by,
                    'updated_by' => $submitted_by,
                ];
                $indentChildStored = IndentChildren::create($indentChild);
            }
            DB::commit();
            $data = [$indentStoredMaster];
            return response([
                'message' => 'Data stored success',
                'status' => $data,
            ], 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return response([
                'message' => $th->getMessage(),
                'status' => 'failed'
            ], 400);
        }
    }


    public function show($id)
    {
        $indent = ItemMasterModel::with([
            'item_indent_child'
        ])
            ->findOrFail($id);

        return response()->json([
            'indent' => $indent
        ]);
    }
}