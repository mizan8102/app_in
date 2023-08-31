<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IOCInputService;
use App\Models\IOCItemDetail;
use App\Models\IOCPriceDeclaration;
use App\Models\IOCValueAddedService;
use App\Models\SvUOM;
use App\Models\VarItemInfo;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IOCPriceDecController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return request('value')? IOCPriceDeclaration::select('var_item_info.id','var_item_info.display_itm_name')->leftJoin("var_item_info","var_item_info.id","item_info_id")->groupBY('item_info_id')->get():
            IOCPriceDeclaration::all();
    }

    /**
     * Display a listing of the resource by pagination of user input
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getIOCPriceDeclWithParam(Request $request)
    {

        if ($request->limit <= 0) {
            $limit = 10;
        } else {
            $limit = $request->limit;
        }

        $query = DB::table('GetLetestIOCMaster');
        if($request->itemId){
            if($request->itemShowAll){
                return DB::table('GetIOCMasterListByItem')
                    ->where('iocItemId',$request->itemId)
                    ->paginate($limit);
            }
            else{
                return DB::table('GetLetestIOCMaster')
                    ->where('iocItemId',$request->itemId)
                    ->paginate($limit);
            }
        }
        if($request->masterGroupId){
            $query->where('masterGroupId',$request->masterGroupId);
        }
        if($request->groupId){
            $query->where('GroupId',$request->groupId);
        }
        if($request->subGroupId){
            $query->where('subGroupId',$request->subGroupId);
        }
        return $query->paginate($limit);

        // $search_input = $request->search;
        // if($request->showAll){
        //     return IOCPriceDeclaration::latest()
        //     ->where('item_information_id', 'like', '%' . $search_input . '%')
        //     ->orWhere('prc_decl_number', 'like', '%' . $search_input . '%')
        //     ->paginate($limit);
        // }else{
        //     if ($search_input) {
        //         return IOCPriceDeclaration::
        //             where('item_information_id', 'like', '%' . $search_input . '%')
        //             ->orderBy('created_at','DESC')
        //             ->orWhere('prc_decl_number', 'like', '%' . $search_input . '%')
        //             ->groupBy('item_information_id')
        //             ->paginate($limit);
        //     } else {
        //         return IOCPriceDeclaration::
        //         orderBy('created_at','DESC')
        //         ->groupBy('item_information_id')
        //         ->paginate($limit);

        //     }
        // }

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
            "prc_decl_name" => "required",
            "prc_decl_number" => "required",
            "effective_from" => "required",
            "item_information_id" => "required|integer|min:1,item_info_id|exists:var_item_info,id",
            "date_of_submission" => "required",
            "qty" => "required",
            "item_info_rows.*.item_information_id"=>"required|numeric|regex:/^\d+(\.\d{1,2})?$/|exists:var_item_info,id",
            "item_info_rows.*.consumption_uom"=>"required|exists:5m_sv_uom,id",
            "item_info_rows.*.consumption"=>"required",
            "item_info_rows.*.purchase_rate"=>"required",
            "item_info_rows.*.wastage"=>"required",
            "item_info_rows.*.wastage_percent"=>"required",
            "item_info_rows.*.cost"=>"required",
            // "input_service_rows.*.input_service_id"=>"required|integer|exists:var_item_info,id",
            // "input_service_rows.*.input_service_amount"=>"required",
//            "input_service_rows.*.rebatable"=>"required",
            // "value_added_rows.*.value_adding_service_id"=>"required",
            // "value_added_rows.*.value_adding_service_amount"=>"required",
        ]);
        if($validator->fails() ){
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        $effective_from =  Carbon::parse($request->effective_from);
        $date_of_submission =  Carbon::parse($request->date_of_submission);
        try {
            return DB::transaction(function () use ($request, $effective_from, $date_of_submission) {
                $ioc_price_master= IOCPriceDeclaration::create([
                    "prc_decl_name" => $request->prc_decl_name,
                    "quantity" => $request->calculation_qty,
                    "ioc_qty" => $request->qty,
                    "prc_decl_number" => $request->prc_decl_number,
                    "effective_from" => $effective_from,
                    "item_info_id" => $request->item_information_id,
                    "total_cost_rm" => $request->total_cost_rm,
                    "total_overhead_cost" => $request->total_overhead_cost,
                    "total_monthly_srv_cost" => $request->total_monthly_srv_cost,
                    "is_manufactured_itm" => 0,
                    "grand_total_cost" => $request->total_cost,
                    "total_cost" => $request->total_cost_unit,
                    "date_of_submission" => $date_of_submission,
                    "remarks" => $request->remarks,
                    "company_id" => Auth::user()->company_id,
                    "created_by" => Auth::user()->id,
                ]);
                VarItemInfo::where('id',$ioc_price_master->item_info_id)->update([
                    "estimate_time" =>date("H:i",strtotime($request->estimate_time)) ,
                    "ioc_rate" => $request->total_cost_unit,
                    "ioc_ref_id" => $ioc_price_master->id
                ]);
                foreach ($request['item_info_rows'] as $item) {
                    $ioc_item = IOCItemDetail::create([
                        "ioc_price_declaration_id" => $ioc_price_master->id,
                        "item_info_id" => $item['item_information_id'],
                        "consumption_uom" => $item['consumption_uom'],
                        "consumption" => $item['consumption'],
                        "consumption_single_unit" => $item['unit_cons'],
                        "purchase_rate" => $item["purchase_rate"],
                        "wastage" => $item['wastage'],
                        "wastage_percent" =>  $item['wastage_percent'],
                        "calculative_amt" =>  $item['cost'],
                        "ioc_amt" =>  $item['unit_cost'],
                        "created_by" => Auth::user()->id,
                    ]);
                    VarItemInfo::where('id',$ioc_item->item_info_id)->update([
                        "ioc_rate" => $item["purchase_rate"]
                    ]);
                }
                if(count($request['input_service_rows'])>0){
                     foreach ($request['input_service_rows'] as $item) {
                        $ioc_input = IOCInputService::create([
                            "ioc_price_declaration_id" => $ioc_price_master->id,
                            "input_service_id" => $item['input_service_id'],
                            "input_service_amount" => $item['input_service_amount'],
                            "ioc_amt" => $item['input_service_unit_amout'],
                            "created_by" => Auth::user()->id,
                        ]);
                    }
                }
               
                if(count($request['value_added_rows']) > 1){
                    foreach ($request['value_added_rows'] as $item) {
                    $ioc_value = IOCValueAddedService::create([
                            "ioc_price_declaration_id" => $ioc_price_master->id,
                            "value_adding_service_id" => $item['value_adding_service_id'],
                            "vas_calculative_amt" => $item['value_adding_service_amount'],
                            "vas_ioc_amt" => $item['ioc_unit_amount'],
                            "created_by" => Auth::user()->id,
                        ]);
                    }
                }
                
                return $ioc_price_master;
            });
            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();
            return response()->json(["status" => "failed", "message" => $exception->getMessage()], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return IOCPriceDeclaration::select(
            "tran01a_ioc_price_declaration.id",
            "tran01a_ioc_price_declaration.prc_decl_name",
            "tran01a_ioc_price_declaration.prc_decl_number",
            "tran01a_ioc_price_declaration.effective_from",
            "var_item_info.uom_id",
            "tran01a_ioc_price_declaration.ioc_qty as qty",
            "tran01a_ioc_price_declaration.quantity as calculation_qty",
            "tran01a_ioc_price_declaration.date_of_submission",
            "tran01a_ioc_price_declaration.item_info_id as item_information_id",
            "tran01a_ioc_price_declaration.grand_total_cost",
            "tran01a_ioc_price_declaration.remarks",
            "tran01a_ioc_price_declaration.grand_total_cost",
            "tran01a_ioc_price_declaration.total_cost_rm",
            "tran01a_ioc_price_declaration.total_cost",
            "tran01a_ioc_price_declaration.total_overhead_cost",
            "tran01a_ioc_price_declaration.total_monthly_srv_cost",
            "var_item_info.display_itm_name",
            "var_item_info.estimate_time",
            "5m_sv_uom.uom_short_code"
        )
            ->leftJoin('var_item_info','var_item_info.id','=','tran01a_ioc_price_declaration.item_info_id')
            ->leftJoin('5m_sv_uom','var_item_info.uom_id','=','5m_sv_uom.id')
            ->with(['itemInfoRows' => function ($query) {
                return $query->with('uoms')->select(
                    "*"
                )->leftJoin('var_item_info','var_item_info.id','=','tran01b_ioc_item_details.item_info_id')
                    ->leftJoin('5m_sv_uom','var_item_info.uom_id','=','5m_sv_uom.id');
            }, 'inputServiceRows' => function ($query) {
                return $query->select(
                    "*"
                )->leftJoin('5t_sv_input_service','5t_sv_input_service.id','tran01c_ioc_input_service.input_service_id');
            }, 'valueAddedRows' => function ($query) {
                return $query->select(
                    "*"
                )->leftJoin('5r_sv_vas','5r_sv_vas.id','tran01d_ioc_value_adding_svc.value_adding_service_id');
            }, ])
            ->where('tran01a_ioc_price_declaration.id', $id)
            ->first();
    }
    public function getUomsforIoc($id){
        return SvUOM::where('id',$id)->first();
    }
}
