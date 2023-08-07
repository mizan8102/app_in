<?php

namespace App\Http\Controllers\v2\VarItem;

use App\Http\Controllers\Controller;
use App\Models\ProductGroup;
use App\Models\SubGroup;
use App\Models\SvUOM;
use App\Models\VarItemDetails;
use App\Models\VarItemInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VarItemInformation extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $items = VarItemInfo::select("*")
            ->with('sv_uom')
            ->whereDoesntHave('issue_child')
            ->whereDoesntHave('receive_child')
            ->get();
    }

    public function getVarItemsParam(Request $request){
        if($request->prod_types){
            return VarItemInfo::select("*")
                ->whereIn('prod_type_id', $request->prod_types)
                ->get();
        }else{
            return VarItemInfo::select("*")->get();
        }
    }

    public function getItemSubGroups(Request $request){
        return DB::table('var_item_sub_group')
            ->select(
                'var_item_sub_group.itm_sub_grp_id',
                'var_item_sub_group.itm_sub_grp_des',
                'var_item_sub_group.itm_sub_grp_des_bn',
            )
            ->get();
    }

    public function getAllVarItemsParam(Request $request){
        $search = request('search', '');
        $limit = request('limit', 10);
        $catagory=request('catagory','');
        $type=request('type','');
        $master_group=request('master_group','');
        $group=request('group','');
        $sub_group=request('sub_group','');
        $mm=ProductGroup::where('itm_mstr_grp_id',$master_group)->pluck('id');
        if($sub_group){
            return VarItemInfo::select(
                'var_item_info.id as item_information_id',
                'var_item_info.display_itm_name',
                'var_item_info.display_itm_name_bn',
                'var_item_info.itm_code',
                'var_item_info.*',
                'var_item_info.display_itm_code',
                'var_item_info.display_itm_code_bn',
                'var_item_info.mushak_itm_name',
                'var_item_info.mushak_itm_name_bn',
                'var_item_info.itm_sub_grp_id',
                'var_item_info.current_rate',
                'var_item_info.safety_level',
                'var_item_info.reorder_level',
                'var_item_info.is_active',
                'uom.local_desc as uom_local_desc',
                'uom.uom_short_code as uom_short_code',
                'trns_unit.local_desc as trns_local_desc',
                'stock_unit.local_desc as stock_local_desc',
                'sales_unit.local_desc as sales_local_desc',
                'cs_company_master.comp_name',
                'var_hs_code.hs_code',
                '5f_sv_product_type.prod_type_name',
                '5f_sv_product_type.id as pp_type_id',
                '5h_sv_product_category.id as prod_cat_id',
            )
                ->leftJoin('5m_sv_uom as uom','uom.id','=','var_item_info.uom_id')
                ->leftJoin('5m_sv_uom as trns_unit','trns_unit.id','=','var_item_info.trns_unit_id')
                ->leftJoin('5m_sv_uom as stock_unit','stock_unit.id','=','var_item_info.stock_unit_id')
                ->leftJoin('5m_sv_uom as sales_unit','sales_unit.id','=','var_item_info.sales_unit_id')
                ->leftJoin('cs_company_master','cs_company_master.id','=','var_item_info.company_id')
                ->leftJoin('var_hs_code','var_hs_code.hs_code_id','=','var_item_info.hs_code_id')
                ->leftJoin('var_item_sub_group','var_item_sub_group.id','=','var_item_info.itm_sub_grp_id')
                ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','=','var_item_info.prod_type_id')
                ->leftJoin('5h_sv_product_category','5f_sv_product_type.prod_cat_id','=','5h_sv_product_category.id')
                ->with(["sub_group"=>function($fn){
                    $fn->with(["product_group"=>function($query){
                        $query->with("masterGroup");
                    }]);
                }])
                ->where('var_item_info.itm_sub_grp_id',$sub_group)
                // ->where('5f_sv_product_type.id',$type)
                ->where('var_item_info.display_itm_name', 'like', '%' . $search . '%')
                ->paginate($limit);
        }else if($group){
            $sub=SubGroup::where('itm_grp_id',$group)->pluck('id');
            return VarItemInfo::select(
                'var_item_info.id as item_information_id',
                'var_item_info.display_itm_name',
                'var_item_info.display_itm_name_bn',
                'var_item_info.itm_code',
                'var_item_info.*',
                'var_item_info.display_itm_code',
                'var_item_info.display_itm_code_bn',
                'var_item_info.mushak_itm_name',
                'var_item_info.mushak_itm_name_bn',
                'var_item_info.itm_sub_grp_id',
                'var_item_info.current_rate',
                'var_item_info.safety_level',
                'var_item_info.reorder_level',
                'var_item_info.is_active',
                'uom.local_desc as uom_local_desc',
                'uom.uom_short_code as uom_short_code',
                'trns_unit.local_desc as trns_local_desc',
                'stock_unit.local_desc as stock_local_desc',
                'sales_unit.local_desc as sales_local_desc',
                'cs_company_master.comp_name',
                'var_hs_code.hs_code',
                '5f_sv_product_type.prod_type_name',
                '5f_sv_product_type.id as pp_type_id',
                '5h_sv_product_category.id as prod_cat_id',
            )
                ->leftJoin('5m_sv_uom as uom','uom.id','=','var_item_info.uom_id')
                ->leftJoin('5m_sv_uom as trns_unit','trns_unit.id','=','var_item_info.trns_unit_id')
                ->leftJoin('5m_sv_uom as stock_unit','stock_unit.id','=','var_item_info.stock_unit_id')
                ->leftJoin('5m_sv_uom as sales_unit','sales_unit.id','=','var_item_info.sales_unit_id')
                ->leftJoin('cs_company_master','cs_company_master.id','=','var_item_info.company_id')
                ->leftJoin('var_hs_code','var_hs_code.hs_code_id','=','var_item_info.hs_code_id')
                ->leftJoin('var_item_sub_group','var_item_sub_group.id','=','var_item_info.itm_sub_grp_id')
                ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','=','var_item_info.prod_type_id')
                ->leftJoin('5h_sv_product_category','5f_sv_product_type.prod_cat_id','=','5h_sv_product_category.id')
                ->with(["sub_group"=>function($fn){
                    $fn->with(["product_group"=>function($query){
                        $query->with("masterGroup");
                    }]);
                }])
                ->whereIn('var_item_info.itm_sub_grp_id',$sub)
                // ->whereIn('var_item_sub_group.itm_grp_id',$mm)
                // ->where('5f_sv_product_type.id',$type)
                ->where('var_item_info.display_itm_name', 'like', '%' . $search . '%')
                ->paginate($limit);
        }else if($master_group){


            return VarItemInfo::select(
                'var_item_info.id as item_information_id',
                'var_item_info.display_itm_name',
                'var_item_info.display_itm_name_bn',
                'var_item_info.itm_code',
                'var_item_info.*',
                'var_item_info.display_itm_code',
                'var_item_info.display_itm_code_bn',
                'var_item_info.mushak_itm_name',
                'var_item_info.mushak_itm_name_bn',
                'var_item_info.itm_sub_grp_id',
                'var_item_info.current_rate',
                'var_item_info.safety_level',
                'var_item_info.reorder_level',
                'var_item_info.is_active',
                'uom.local_desc as uom_local_desc',
                'uom.uom_short_code as uom_short_code',
                'trns_unit.local_desc as trns_local_desc',
                'stock_unit.local_desc as stock_local_desc',
                'sales_unit.local_desc as sales_local_desc',
                'cs_company_master.comp_name',
                'var_hs_code.hs_code',
                '5f_sv_product_type.prod_type_name',
                '5f_sv_product_type.id as pp_type_id',
                '5h_sv_product_category.id as prod_cat_id',
            )
                ->leftJoin('5m_sv_uom as uom','uom.id','=','var_item_info.uom_id')
                ->leftJoin('5m_sv_uom as trns_unit','trns_unit.id','=','var_item_info.trns_unit_id')
                ->leftJoin('5m_sv_uom as stock_unit','stock_unit.id','=','var_item_info.stock_unit_id')
                ->leftJoin('5m_sv_uom as sales_unit','sales_unit.id','=','var_item_info.sales_unit_id')
                ->leftJoin('cs_company_master','cs_company_master.id','=','var_item_info.company_id')
                ->leftJoin('var_hs_code','var_hs_code.hs_code_id','=','var_item_info.hs_code_id')
                ->leftJoin('var_item_sub_group','var_item_sub_group.id','=','var_item_info.itm_sub_grp_id')
                ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','=','var_item_info.prod_type_id')
                ->leftJoin('5h_sv_product_category','5f_sv_product_type.prod_cat_id','=','5h_sv_product_category.id')
                ->with(["sub_group"=>function($fn){
                    $fn->with(["product_group"=>function($query){
                        $query->with("masterGroup");
                    }]);
                }])
                ->whereIn('var_item_sub_group.itm_grp_id',$mm)
                // ->where('5f_sv_product_type.id',$type)
                ->where('var_item_info.display_itm_name', 'like', '%' . $search . '%')
                ->paginate($limit);
        }else if($type){
            return VarItemInfo::select(
                'var_item_info.id as item_information_id',
                'var_item_info.display_itm_name',
                'var_item_info.display_itm_name_bn',
                'var_item_info.itm_code',
                'var_item_info.*',
                'var_item_info.display_itm_code',
                'var_item_info.display_itm_code_bn',
                'var_item_info.mushak_itm_name',
                'var_item_info.mushak_itm_name_bn',
                'var_item_info.itm_sub_grp_id',
                'var_item_info.current_rate',
                'var_item_info.safety_level',
                'var_item_info.reorder_level',
                'var_item_info.is_active',
                'uom.local_desc as uom_local_desc',
                'uom.uom_short_code as uom_short_code',
                'trns_unit.local_desc as trns_local_desc',
                'stock_unit.local_desc as stock_local_desc',
                'sales_unit.local_desc as sales_local_desc',
                'cs_company_master.comp_name',
                'var_hs_code.hs_code',
                '5f_sv_product_type.prod_type_name',
                '5f_sv_product_type.id as pp_type_id',
                '5h_sv_product_category.id as prod_cat_id',
            )
                ->leftJoin('5m_sv_uom as uom','uom.id','=','var_item_info.uom_id')
                ->leftJoin('5m_sv_uom as trns_unit','trns_unit.id','=','var_item_info.trns_unit_id')
                ->leftJoin('5m_sv_uom as stock_unit','stock_unit.id','=','var_item_info.stock_unit_id')
                ->leftJoin('5m_sv_uom as sales_unit','sales_unit.id','=','var_item_info.sales_unit_id')
                ->leftJoin('cs_company_master','cs_company_master.id','=','var_item_info.company_id')
                ->leftJoin('var_hs_code','var_hs_code.hs_code_id','=','var_item_info.hs_code_id')
                ->leftJoin('var_item_sub_group','var_item_sub_group.id','=','var_item_info.itm_sub_grp_id')
                ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','=','var_item_info.prod_type_id')
                ->leftJoin('5h_sv_product_category','5f_sv_product_type.prod_cat_id','=','5h_sv_product_category.id')
                ->with(["sub_group"=>function($fn){
                    $fn->with(["product_group"=>function($query){
                        $query->with("masterGroup");
                    }]);
                }])
                ->where('5f_sv_product_type.id',$type)
                ->where('var_item_info.display_itm_name', 'like', '%' . $search . '%')
                ->paginate($limit);
        }else if($catagory){
            return VarItemInfo::select(
                'var_item_info.id as item_information_id',
                'var_item_info.display_itm_name',
                'var_item_info.display_itm_name_bn',
                'var_item_info.itm_code',
                'var_item_info.*',
                'var_item_info.display_itm_code',
                'var_item_info.display_itm_code_bn',
                'var_item_info.mushak_itm_name',
                'var_item_info.mushak_itm_name_bn',
                'var_item_info.itm_sub_grp_id',
                'var_item_info.current_rate',
                'var_item_info.safety_level',
                'var_item_info.reorder_level',
                'var_item_info.is_active',
                'uom.local_desc as uom_local_desc',
                'uom.uom_short_code as uom_short_code',
                'trns_unit.local_desc as trns_local_desc',
                'stock_unit.local_desc as stock_local_desc',
                'sales_unit.local_desc as sales_local_desc',
                'cs_company_master.comp_name',
                'var_hs_code.hs_code',
                '5f_sv_product_type.prod_type_name',
                '5f_sv_product_type.id as pp_type_id',
                '5h_sv_product_category.id as prod_cat_id',
            )
                ->leftJoin('5m_sv_uom as uom','uom.id','=','var_item_info.uom_id')
                ->leftJoin('5m_sv_uom as trns_unit','trns_unit.id','=','var_item_info.trns_unit_id')
                ->leftJoin('5m_sv_uom as stock_unit','stock_unit.id','=','var_item_info.stock_unit_id')
                ->leftJoin('5m_sv_uom as sales_unit','sales_unit.id','=','var_item_info.sales_unit_id')
                ->leftJoin('cs_company_master','cs_company_master.id','=','var_item_info.company_id')
                ->leftJoin('var_hs_code','var_hs_code.hs_code_id','=','var_item_info.hs_code_id')
                ->leftJoin('var_item_sub_group','var_item_sub_group.id','=','var_item_info.itm_sub_grp_id')
                ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','=','var_item_info.prod_type_id')
                ->leftJoin('5h_sv_product_category','5f_sv_product_type.prod_cat_id','=','5h_sv_product_category.id')
                ->with(["sub_group"=>function($fn){
                    $fn->with(["product_group"=>function($query){
                        $query->with("masterGroup");
                    }]);
                }])
                ->where('5f_sv_product_type.prod_cat_id',$catagory)

                ->where('var_item_info.display_itm_name', 'like', '%' . $search . '%')

                ->paginate($limit);
        }else{
            return VarItemInfo::select(
                'var_item_info.id as item_information_id',
                'var_item_info.display_itm_name',
                'var_item_info.display_itm_name_bn',
                'var_item_info.itm_code',
                'var_item_info.*',
                'var_item_info.display_itm_code',
                'var_item_info.display_itm_code_bn',
                'var_item_info.mushak_itm_name',
                'var_item_info.mushak_itm_name_bn',
                'var_item_info.itm_sub_grp_id',
                'var_item_info.current_rate',
                'var_item_info.safety_level',
                'var_item_info.reorder_level',
                'var_item_info.is_active',
                'uom.local_desc as uom_local_desc',
                'uom.uom_short_code as uom_short_code',
                'trns_unit.local_desc as trns_local_desc',
                'stock_unit.local_desc as stock_local_desc',
                'sales_unit.local_desc as sales_local_desc',
                'cs_company_master.comp_name',
                'var_hs_code.hs_code',
                '5f_sv_product_type.prod_type_name',
                '5f_sv_product_type.id as pp_type_id',
                '5h_sv_product_category.id as prod_cat_id',
            )
                ->leftJoin('5m_sv_uom as uom','uom.id','=','var_item_info.uom_id')
                ->leftJoin('5m_sv_uom as trns_unit','trns_unit.id','=','var_item_info.trns_unit_id')
                ->leftJoin('5m_sv_uom as stock_unit','stock_unit.id','=','var_item_info.stock_unit_id')
                ->leftJoin('5m_sv_uom as sales_unit','sales_unit.id','=','var_item_info.sales_unit_id')
                ->leftJoin('cs_company_master','cs_company_master.id','=','var_item_info.company_id')
                ->leftJoin('var_hs_code','var_hs_code.hs_code_id','=','var_item_info.hs_code_id')
                ->leftJoin('var_item_sub_group','var_item_sub_group.id','=','var_item_info.itm_sub_grp_id')
                ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','=','var_item_info.prod_type_id')
                ->leftJoin('5h_sv_product_category','5f_sv_product_type.prod_cat_id','=','5h_sv_product_category.id')
                ->with(["sub_group"=>function($fn){
                    $fn->with(["product_group"=>function($query){
                        $query->with("masterGroup");
                    }]);
                }])
                ->where('var_item_info.display_itm_name', 'like', '%' . $search . '%')

                ->paginate($limit);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "itm_sub_grp_id" => "required",
            "itm_code" => "required",
            "prod_type_id" => "required",
//            "prod_cat_id" => "required",
            "display_itm_code" => "required",
            "display_itm_name" => "required",
            "display_itm_name_bn" => "required",
            // "mushak_itm_name" => "required",
            // "mushak_itm_name_bn" => "required",
            "uom_id" => "required",
            "stock_unit_id" => "required",
            "sales_unit_id" => "required",
            "is_active" => "required",
        ]);

        if ($validator->fails()) {
            return $this->validationErrors($validator->errors())->setStatusCode(406);
        }
        $data=array();
        $relativePath="";
        if (isset($request->image)) {
            $relativePath  = $this->saveImage($request->image);
//            $data['image'] = $relativePath;
        }
        DB::transaction();
        try {

            $varitem = VarItemInfo::create([
                "itm_sub_grp_id" =>$request->itm_sub_grp_id,
                "prod_type_id" => $request->prod_type_id,
//                "prod_cat_id" => $request->prod_cat_id,
                "itm_code" =>               $request->itm_code,

                "display_itm_code" =>       $request->display_itm_code,
                "display_itm_code_bn" =>    $request->display_itm_code_bn,
                "display_itm_name" =>       $request->display_itm_name,
                "display_itm_name_bn" =>    $request->display_itm_name_bn,
                "mushak_itm_name" =>        $request->mushak_name,
                "mushak_itm_name_bn" =>     $request->mushak_name_bn,
                "uom_id" =>                 $request->uom_id,
                "trns_unit_id" =>           $request->trns_unit_id,
                "stock_unit_id" =>          $request->stock_unit_id,
                "sales_unit_id" =>          $request->sales_unit_id,
                "is_active" =>              $request->is_active,
                "current_rate" =>           $request->current_rate,
                "safety_level" =>           $request->safety_level,
                "reorder_level" =>          $request->reorder_level,
                "company_id" =>             Auth::user()->company_id,
                "created_by" =>             Auth::user()->id,
            ]);
            $dd=VarItemDetails::create([
                "item_information_id" => $varitem->id,
                "description" =>$request->item_desc,
                "description_bn" =>$request->item_desc_bn,
                "item_image" =>$relativePath,
            ]);
            DB::commit();
            return response()->json([
                "status" => "success",
                "error" => false,
                "inserted_id" => $varitem->id,
                "message" => "Success! Var item created."
            ], 201);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json(["status" => "failed", "message" => $exception->getMessage()], 404);
        }
    }

    private function saveImage($image)
    {
        // Check if image is valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            // Take out the base64 encoded text without mime type
            $image = substr($image, strpos($image, ',') + 1);
            // Get file extension
            $type = strtolower($type[1]); // jpg, png, gif

            // Check if file is an image
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }

        $dir = 'items/';
        $file = Str::random() . '.' . $type;
        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }
        file_put_contents($relativePath, $image);

        return $relativePath;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return VarItemInfo::select(
            'var_item_info.id as item_information_id',
            'var_item_info.display_itm_name',
            'var_item_info.display_itm_name_bn',
            'var_item_info.itm_code',
            'var_item_info.*',
            'var_item_info.display_itm_code',
            'var_item_info.display_itm_code_bn',
            'var_item_info.mushak_itm_name',
            'var_item_info.mushak_itm_name_bn',
            'var_item_info.itm_sub_grp_id',
            'var_item_info.current_rate',
            'var_item_info.safety_level',
            'var_item_info.reorder_level',
            'var_item_info.is_active',
            'uom.local_desc as uom_local_desc',
            'uom.uom_short_code as uom_short_code',
            'trns_unit.local_desc as trns_local_desc',
            'stock_unit.local_desc as stock_local_desc',
            'sales_unit.local_desc as sales_local_desc',
            'cs_company_master.comp_name',
            'var_hs_code.hs_code',
            '5f_sv_product_type.prod_type_name',
            '5f_sv_product_type.id as pp_type_id',
            '5h_sv_product_category.id as prod_cat_id',
        )
            ->leftJoin('5m_sv_uom as uom','uom.id','=','var_item_info.uom_id')
            ->leftJoin('5m_sv_uom as trns_unit','trns_unit.id','=','var_item_info.trns_unit_id')
            ->leftJoin('5m_sv_uom as stock_unit','stock_unit.id','=','var_item_info.stock_unit_id')
            ->leftJoin('5m_sv_uom as sales_unit','sales_unit.id','=','var_item_info.sales_unit_id')
            ->leftJoin('cs_company_master','cs_company_master.id','=','var_item_info.company_id')
            ->leftJoin('var_hs_code','var_hs_code.hs_code_id','=','var_item_info.hs_code_id')
            ->leftJoin('var_item_sub_group','var_item_sub_group.id','=','var_item_info.itm_sub_grp_id')
            ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','=','var_item_info.prod_type_id')
            ->leftJoin('5h_sv_product_category','5f_sv_product_type.prod_cat_id','=','5h_sv_product_category.id')
            ->with(["sub_group"=>function($fn){
                $fn->with(["product_group"=>function($query){
                    $query->with("masterGroup");
                }]);
            }])
            ->where('var_item_info.id',$id)->first();

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = VarItemDetails::where('item_information_id',$id)->first();
        $varitem=array();


        $validator = Validator::make($request->all(), [
            "itm_sub_grp_id" => "required",
            "itm_code" => "required",
            "prod_type_id" => "required",
//            "prod_cat_id" => "required",
            "display_itm_code" => "required",
            "display_itm_name" => "required",
            "display_itm_name_bn" => "required",
            // "mushak_itm_name" => "required",
            // "mushak_itm_name_bn" => "required",
            "uom_id" => "required",
            "stock_unit_id" => "required",
            "sales_unit_id" => "required",
            "is_active" => "required",
        ]);

        if ($validator->fails()) {
            return $this->validationErrors($validator->errors())->setStatusCode(406);
        }
        $varitem['itm_sub_grp_id'] =        $request->itm_sub_grp_id;
        $varitem["prod_type_id"] = $request->prod_type_id;
//                $varitem["prod_cat_id"] = $request->prod_cat_id;
        $varitem['itm_code'] =              $request->itm_code;
        $varitem['display_itm_code'] =      $request->display_itm_code;
        $varitem['display_itm_code_bn'] =   $request->display_itm_code_bn;
        $varitem['display_itm_name'] =      $request->display_itm_name;
        $varitem['display_itm_name_bn'] =   $request->display_itm_name_bn;
        $varitem['mushak_itm_name'] =       $request->mushak_name;
        $varitem['mushak_itm_name_bn'] =    $request->mushak_name_bn;
        $varitem['uom_id'] =                $request->uom_id;
        $varitem['trns_unit_id'] =          $request->trns_unit_id;
        $varitem['stock_unit_id'] =         $request->stock_unit_id;
        $varitem['sales_unit_id'] =         $request->sales_unit_id;
        $varitem['is_active'] =             $request->is_active;
        $varitem['current_rate'] =          $request->current_rate;
        $varitem['safety_level'] =          $request->safety_level;
        $varitem['reorder_level'] =         $request->reorder_level;
        $varitem['updated_by'] =            Auth::user()->id;
        // Check if image was given and save on local file system
        $detailsItem=array();
        if (isset($request->image)) {
//                return $request->image;
            $relativePath = $this->saveImage($request->image);
            $detailsItem['item_image'] = $relativePath;

            // If there is an old image, delete it
            if ($data->item_image) {
                $absolutePath = public_path($data->item_image);
                File::delete($absolutePath);
            }
        }

        $detailsItem['description'] = $request->item_desc;
        $detailsItem['description_bn'] =  $request->item_desc_bn;


        VarItemInfo::where('id',$id)->update($varitem);
        VarItemDetails::where('item_information_id',$id)->update($detailsItem);

        return response()->json([
            "status" => "success",
            "error" => false,

            "message" => "Success! Var item updated."
        ], 201);

        return response()->json(["status" => "failed", "error" => true, "message" => "Failed! no var item found."], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $varitem = VarItemInfo::where('id', $id)->delete();
        if ($varitem) {
            return response()->json(["status" => "success", "error" => false, "message" => "Item is deleted successfully."], 200);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed! item not found."], 404);
    }


    public function changeStatus($id)
    {
        $varitem = VarItemInfo::find($id);
        if ($varitem) {

            if ($varitem['is_active'] == 1) {
                $varitem['is_active'] = 0;
            } else {
                $varitem['is_active'] = 1;
            }

            $varitem->save();

            return response()->json([
                "status" => "success",
                "error" => false,
                "updated_id" => $varitem->item_information_id,
                "message" => "Success! Status updated."
            ], 201);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed! no var item found."], 404);
    }


    public function getUomFromItemUom(Request $request){

        $getSetId = SvUOM::where('id', $request->item_id)->first();

        $getUomsBySet = SvUOM::where('uom_set_id', $getSetId->uom_set_id)->get();

        return $getUomsBySet;

    }

    public function getUomGroupWise($id){
//        $uom=ProductGroup::leftJoin('')
    }
}
