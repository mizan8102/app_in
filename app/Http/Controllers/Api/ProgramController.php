<?php

namespace App\Http\Controllers\Api;

use App\Helpers\IdgenerateHelper;
use App\Http\Controllers\Controller;
use App\Models\OrderChild;
use App\Models\OrderMaster;
use App\Models\OrderStatus;
use App\Models\PProgramCard as ModelsPProgramCard;
use App\Models\PProgramMaster;
use App\Models\PProgramMenu as ModelsPProgramMenu;
use App\Models\ProgramSession;
use App\Models\RCard;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search', '');
        $perPage = request('perPage', 10);
        $data = DB::table('trns00g_order_master')
            ->leftJoin('r_floor', 'trns00g_order_master.floor_id', '=', 'r_floor.id')
            ->leftJoin('program_sessions', 'trns00g_order_master.program_session_id', '=', 'program_sessions.id')
            ->leftJoin('cs_customer_details', 'trns00g_order_master.customer_id', '=', 'cs_customer_details.id')
            ->leftJoin('cs_customer_contact_info', 'cs_customer_details.id', '=', 'cs_customer_contact_info.customer_id')
            ->leftJoin('5z2_program_type', 'trns00g_order_master.program_type_id', '=', '5z2_program_type.id')
            ->leftJoin('p_program_menu', 'trns00g_order_master.id', '=', 'p_program_menu.program_master_id')
            ->leftJoin('p_program_card', 'trns00g_order_master.id', '=', 'p_program_card.program_master_id')
            ->leftJoin('var_item_info', 'p_program_menu.menu_id', '=', 'var_item_info.id')
            ->leftJoin('5m_sv_uom', 'var_item_info.uom_id', '=', '5m_sv_uom.id')
            ->leftJoin('users', 'trns00g_order_master.created_by', '=', 'users.id')
            // ->leftJoin('trns50a_payment_master', 'trns00g_order_master.id', '=', 'trns50a_payment_master.trns00g_order_master_id')
            // ->leftJoin('r_card','p_program_card.card_id','=','r_card.id')
            ->select(
                'trns00g_order_master.*',
                'trns00g_order_master.total_amount',
                'trns00g_order_master.program_type_id',
                'r_floor.floor_name',
                'r_floor.floor_name_bn',
                'cs_customer_details.customer_name',
                'cs_customer_details.phone_number',
                'cs_customer_details.customer_name_bn',
                '5z2_program_type.program_type_name',
                '5z2_program_type.program_type_name_bn',
                // 'trns50a_payment_master.paid_amount',
                'program_sessions.session_name',
                'program_sessions.start_time',
                'program_sessions.end_time',
                'cs_customer_contact_info.contact_person',

                'p_program_menu.id AS p_menu_id',
                'p_program_menu.menu_id AS menu_id',
                'p_program_menu.menu_qty AS menu_qty',
                'p_program_menu.menu_rate AS menu_rate',
                'p_program_menu.menu_amount AS menu_amount',
                'var_item_info.display_itm_name',
                'var_item_info.display_itm_name_bn',
                'var_item_info.prod_type_id',
                '5m_sv_uom.id as uom_id',
                '5m_sv_uom.uom_short_code',
                '5m_sv_uom.relative_factor',
                'p_program_card.id AS p_card_id',
                'p_program_card.card_id AS card_id',
                'p_program_card.use_status AS use_status',

                'users.name AS username'

            )
            // ->when($search, function ($query, $search) {
            //     return $query->where('supplier_name', 'like', '%' . $search . '%')
            //         ->orWhere('supplier_name_bn', 'like', '%' . $search . '%');
            // })
            ->paginate($perPage);
        return sendJson('Program List', $data, 200);
        // return OrderMaster::select(
        //     'trns00g_order_master.*',
        //     'floor_name',
        //     'customer_name',
        //     'phone_number',
        //     //            'program_pay_details.due_amount',
        //     'program_sessions.session_name',
        //     'program_sessions.start_time',
        //     'program_sessions.end_time',
        // )
        // ->join('r_floor', 'r_floor.id', '=', 'trns00g_order_master.floor_id')
        // ->join('cs_customer_details', 'trns00g_order_master.customer_id', '=', 'cs_customer_details.id')
        // //        ->join('program_pay_details','trns00g_order_master.id','=','program_pay_details.trns00g_order_master_id')
        // ->leftJoin('program_sessions', 'trns00g_order_master.program_session_id', '=', 'program_sessions.id')
        // // ->select(,'r_floor.*','cs_customer_details.*','trns00g_order_master.*')
        // // ->where('program_date', '>', date('Y-m-d'))
        // ->orderBy('program_date', 'DESC')
        // ->paginate(5);
    }

    public function TodayData()
    {
        return OrderMaster::select(
            'trns00g_order_master.*',
            'cs_company_store_location.sl_name as floor_name',
            'customer_name',
            'phone_number',
            'program_sessions.session_name',
            'program_sessions.start_time',
            'program_sessions.end_time',
        )
            ->leftJoin('program_sessions', 'trns00g_order_master.program_session_id', '=', 'program_sessions.id')
            ->join('cs_company_store_location', 'cs_company_store_location.id', '=', 'trns00g_order_master.floor_id')
            ->join('cs_customer_details', 'trns00g_order_master.customer_id', '=', 'cs_customer_details.id')
            ->whereDate('program_date', Carbon::today())->get();
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
        $validated = $request->validate([
            'store_id'          => 'nullable|exists:cs_company_store_location,id',
            'floor_id'          => 'required',
            'customer_id'       => 'required|exists:cs_customer_details,id',
            'program_type_id'   => 'required|exists:5z2_program_type,id',
            'program_name'      => 'required|max:255',
            'program_name_bn'   => 'required|max:255',
            'hall_room_charge_vat_per' => 'required|numeric',
            'prog_date'         => 'required|date|after_or_equal:today',
            'ride_charge'       => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'service_charge'    => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'discount'          => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'program_session_id'=> 'required|exists:var_program_sessions,id',
            'new_guest'         => 'nullable|boolean',
            'vat_on_food'       => 'required|numeric',
            'remarks'           => 'nullable|max:255',
            'prog_start_time'   => 'nullable|date',
            'prog_end_time'     => 'nullable|date',
            'number_of_guest'   => 'required|integer|min:1',
        
            'food_charge'       => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'total_amount'      => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
          
            'total_amount_with_vat' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'grandTotal'        => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
         
            'is_active'         => 'nullable|boolean',
            'is_print'          => 'nullable|boolean',
            'paid_amount'       => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'childs'            => 'nullable|array',
            // 'childs.*.menu_id' => 'required|integer|exists:var_item_info,id',
            // 'childs.*.uom' => 'required',
            // 'childs.*.prod_type_id' => 'required|integer|exists:5f_sv_product_type,id',
            // 'childs.*.menu_qty' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            // 'childs.*.menu_rate' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            // 'childs.*.menu_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        try {
            DB::beginTransaction();
            $data['is_active'] = 1;
            $data['is_print'] = 0;
            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();
            $data['store_id'] = Auth::user()->store_id;
            $data['created_by'] = Auth::id();
            $data['program_date'] = Carbon::parse($request->prog_date)->format("Y-m-d");
            $data['updated_by'] = " ";
            $data['order_date'] = now();

            $data['customer_id'] = $request->customer_id;
            $data['floor_id'] = $request->floor_id;
            $data['program_session_id'] = $request->program_session_id;
            $data['customer_phone'] = $request->phone;
            $data['program_type_id'] = $request->program_type_id;
            $data['program_name'] = $request->program_name;
            $data['program_name_bn'] = $request->program_name_bn;
            $data['program_start_time'] = $request->prog_start_time;
            $data['program_end_time'] = $request->prog_end_time;
            $data['number_of_guest'] = $request->number_of_guest;
            $data['program_date'] = date('Y-m-d',strtotime($request->prog_date));

            $vat_amount=$request->total_amount- (floatval($request->food_charge)+floatval($request->ride_charge)+floatval($request->service_charge));

            $data['total_amount_without_vat'] = $request->total_amount - $vat_amount;
            $data['total_discount_amount'] = $request->total_discount;
            // $data['total_amount'] = $request->total_amount;
            $data['total_amount_with_vat'] = $request->total_amount;
            $data['total_vat_amount'] = $vat_amount;
            $data['status'] = 0;
            $data['payable'] = $request->total_amount;
            $data['order_type_id'] = $request->order_Status;

            // $data['food_vat_per'] = $request->food_vat_per;
            // $data['hall_room_charge'] = $request->hall_room_charge;
            // $data['hall_room_vat'] = $request->hall_room_vat;
            // $data['food_charge'] = $request->food_charge;
            
           

            // $data['service_charge'] = $request->service_charge;
            $data['remarks'] = $request->remarks;
            // $data['ride_charge'] = $request->ride_charge;
            $program_data = OrderMaster::create($data);
             foreach ($request->childs as $information) {
                OrderChild::create([
                    "order_master_id" => $program_data->id,
                    "branch_id" => Auth::user()->branch_id,
                    "item_info_id" => $information['menu_id'],
                    "uom_id" => $information['uom_id'],
                    "uom_short_code" =>$information['uom'],
                    "relative_factor" => 1,
                    "item_rate" =>$information['menu_rate'],
                    "discount" => $information['dis'],
                    // "discount_percent" => $information->vat_per,
                    "order_qty" => $information['menu_qty'],
                    "item_value_local_curr" => $information['menu_amount'],
                    "total_amount_local_curr" => $information['menu_amount'],
                    "vat_percent" => $information['vat_per'],
                    "vat_amount" => $information['vat_amt'],
                    "created_by" => Auth::id(),
                ]);
                // $information['order_master_id'] = $program_data->id;
                // $information['created_at'] = $program_data->created_at;
                // $information['updated_at'] = $program_data->updated_at;
                // $information['created_by'] = $program_data->created_by;
                // $information['updated_by'] = " ";
                // $this->createProgramMenu($information);
            }
            // ProgramPayDetail::create([
            //     'p_program_master_id' => $program_data->id,
            //     'total_amount' => $data['total_amount'],
            //     'paid_amount' => $request->paid_amount,
            //     'due_amount' => $request->due_amount,
            //     'isPaidstatus' => 0,
            //     'user_id' => auth()->user()->id,
            // ]);
           
            // $lastCard = $this->cardLastestID($program_data->id);

            // for ($i = 0; $i <  intval($program_data['number_of_guest']); $i++) {
            //     $dd = $lastCard + $i;
            //     $arr['program_master_id'] = $program_data->id;

            //     $rcard = RCard::create([
            //         'card_category_id' => 1,
            //         'card_number' => $program_data->id . $program_data->prog_date . $dd,
            //         'card_number_bn' => $program_data->id . $program_data->prog_date . $dd,
            //         'is_free' => 1,
            //         'is_active' => 1,
            //         'created_by' => $program_data->created_by
            //     ]);
            //     $arr['card_id'] = $rcard->id;
            //     ModelsPProgramCard::create($arr);
            // }
            DB::commit();
            return sendJson('Program Has Been Created', $program_data, 200);
        } catch (\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
            return sendJson('Program Create Failed', $exp->getMessage(), 400);
        }
    }

    /**
     * create program menu
     */
    public function createProgramMenu($information)
    {
        $validated = Validator::make($information, [
            'program_master_id'  => 'required',
            'menu_id'  => 'required',
            'menu_qty'  => 'required',
            'menu_rate'  => 'required',
            'menu_amount'  => 'required',
            'created_at'  => 'required',
            'updated_at'  => 'required',
            'created_by'  => 'nullable',
            'updated_by' => 'nullable'
        ]);
        if ($validated->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validated->errors(),
            ], 422);
        }
        return ModelsPProgramMenu::create($validated->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data= OrderMaster::with(['paymentmaster'=>function($query){
            $query->select('trns50a_payment_master.id as pay_id','trns50a_payment_master.*','5x4_paymode_type.*')->leftJoin('5x4_paymode_type','5x4_paymode_type.id','trns50a_payment_master.paymode_id')->orderByDesc('5x4_paymode_type.id');
        }])
        ->select('trns00g_order_master.*','cs_company_store_location.sl_name as floor_name'
        ,'var_program_sessions.session_name','var_program_sessions.start_time','var_program_sessions.end_time','program_type_name','contact_person','customer_name')
        ->leftJoin('cs_company_store_location','cs_company_store_location.id','trns00g_order_master.floor_id')
        ->leftJoin('var_program_sessions','var_program_sessions.id','trns00g_order_master.program_session_id')
        ->leftJoin('5z2_program_type','5z2_program_type.id','trns00g_order_master.program_type_id')
        ->leftJoin('cs_customer_contact_info','cs_customer_contact_info.customer_id','trns00g_order_master.customer_id')
        ->leftJoin('cs_customer_details','cs_customer_details.id','cs_customer_contact_info.customer_id')
        ->find($id);
        $child=OrderChild::select('trns00h_order_child.*','var_item_info.display_itm_name',
        'var_item_master_group.prod_type_id','var_item_master_group.id as master_group_id')
        ->leftJoin('var_item_info','var_item_info.id','trns00h_order_child.item_info_id')
        ->leftJoin('5m_sv_uom', 'var_item_info.uom_id', '=', '5m_sv_uom.id')
        ->leftJoin('var_item_sub_group','var_item_sub_group.id','=','var_item_info.itm_sub_grp_id')
        ->leftJoin('var_item_group', 'var_item_group.id', 'var_item_sub_group.itm_grp_id')
        ->leftJoin('var_item_master_group', 'var_item_group.itm_mstr_grp_id', 'var_item_master_group.id')
        ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','var_item_master_group.prod_type_id')
        ->where('trns00h_order_child.order_master_id',$id)->get();
        $data["menuItem"]=$child->where('prod_type_id', 3)->values();
        $data["ride"]=$child->where('master_group_id', 15)->values();
        $data["service"]=$child->where('master_group_id', 213)->values();


        $data["food_charge"]=$child->where('prod_type_id', 3)->sum('item_value_local_curr');
        $data["ride_charge"]=$child->where('master_group_id', 15)->sum('item_value_local_curr');
        $data["service_charge"]=$child->where('master_group_id', 213)->sum('item_value_local_curr');

        $data["total_dicount"]=$child->sum('discount');

        $data["total_amount"]=floatval($data["food_charge"])+floatval($data["ride_charge"])+floatval($data["service_charge"]);
        $data["order_statues"] = OrderStatus::all();
        return $data;

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

     public function  oldversionUpdate($request,$id){

        $validated = Validator::make($request->all(), [
            'store_id' => 'nullable|exists:cs_company_store_location,id',
            'floor_id' => 'required|exists:r_floor,id',
            'customer_id' => 'required|exists:cs_customer_details,id',
            'program_type_id' => 'required|exists:5z2_program_type,id',
            'program_name' => 'required|max:255',
            'program_name_bn' => 'required|max:255',
            'hall_room_charge_vat_per' => 'required|numeric',
            'prog_date' => 'required|date|after_or_equal:today',
            'ride_charge' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'service_charge' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'discount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'program_session_id' => 'required|exists:program_sessions,id',
            'new_guest' => 'nullable|boolean',
            'vat_on_food' => 'required|numeric',
            'remarks' => 'nullable|max:255',
            'prog_start_time' => 'nullable|date',
            'prog_end_time' => 'nullable|date',
            'number_of_guest' => 'required|integer|min:1',
            'hall_room_charge' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'total_amount_without_vat' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'hall_room_vat' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'food_vat_per' => 'required|numeric',
            'food_charge' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'total_amount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'vat_amount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'total_amount_with_vat' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'grandTotal' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'due_amount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'is_active' => 'nullable|boolean',
            'is_print' => 'nullable|boolean',
            'paid_amount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'childs' => 'nullable|array',
            'childs.*.menu_id' => 'required|integer|exists:var_item_info,id',
            'childs.*.uom' => 'required',
            'childs.*.prod_type_id' => 'required|integer|exists:5f_sv_product_type,id',
            'childs.*.menu_qty' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'childs.*.menu_rate' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'childs.*.menu_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        if ($validated->fails()) {
            return sendJson('validation fails', $validated->errors(), 422);
        }
        $up = OrderMaster::select('status')->where('id', $id)->first();
        if ($up->status == 0) {
            try {
                DB::beginTransaction();
                $data = $validated->validated();
                $dataprog['floor_id'] = $data['floor_id'];
                $dataprog['customer_id'] = $data['customer_id'];
                $dataprog['program_type_id'] = $data['program_type_id'];
                $dataprog['program_name'] = $data['program_name'];
                $dataprog['program_name_bn'] = $data['program_name_bn'];
                $dataprog['hall_room_charge_vat_per'] = $data['hall_room_charge_vat_per'];
                $dataprog['program_date'] = $data['prog_date'];
                $dataprog['vat_on_food'] = $data['vat_on_food'];
                $dataprog['remarks'] = $data['remarks'];

                $dataprog['ride_charge'] = $data['ride_charge'];
                $dataprog['service_charge'] = $data['service_charge'];
                $dataprog['program_session_id'] = $data['program_session_id'];
                $dataprog['grandTotal'] = $data['grandTotal'];

                $dataprog['program_start_time'] = $data['program_start_time'];
                $dataprog['program_end_time'] = $data['program_end_time'];
                $dataprog['number_of_guest'] = $data['number_of_guest'];
                $dataprog['hall_room_charge'] = $data['hall_room_charge'];
                $dataprog['total_amount_without_vat'] = $data['total_amount_without_vat'];
                $dataprog['hall_room_vat'] = $data['hall_room_vat'];
                $dataprog['food_vat_per'] = $data['food_vat_per'];
                $dataprog['total_amount'] = $data['total_amount'];
                $dataprog['vat_amount'] = $data['vat_amount'];
                $dataprog['total_amount_with_vat'] = $data['total_amount_with_vat'];
                $dataprog['hall_room_vat'] = $data['hall_room_vat'];
                // $dataprog['restaurant_master_id'] = $data['store_id'];
                $dataprog['is_print'] = 0;
                $dataprog['updated_at'] = Carbon::now();
                $dataprog['program_date'] = Carbon::parse($request->prog_date)->format("Y-m-d");
                $dataprog['updated_by'] = Auth::id();
                $information = OrderMaster::where('id', $id)->update($dataprog);
                ModelsPProgramMenu::where('program_master_id', $id)->delete();
                foreach ($data['childs'] as $information) {
                    $information['program_master_id'] = $id;
                    $information['updated_at'] = Carbon::now();
                    $information['updated_by'] = Auth::id();
                    $this->createProgramMenu($information);
                }
                if ($request->new_guest) {
                    if ($data['new_guest'] > 0) {
                        $lastCard = $this->cardLastestID($id);

                        for ($i = 0; $i < intval($data['new_guest']); $i++) {
                            $dd = $lastCard + $i;
                            $arr['program_master_id'] = $id;
                            $rcard = RCard::create([
                                'card_category_id' => 1,
                                'card_number' => $id . $dataprog['prog_date'] . $dd,
                                'card_number_bn' => $id . $dataprog['prog_date'] . $dd,
                                'is_free' => 1,
                                'is_active' => 1,
                                'updated_by' => Auth::id()
                            ]);
                            $arr['card_id'] = $rcard->id;
                            ModelsPProgramCard::create($arr);
                        }
                        $guests = $data['number_of_guest'] + $data['new_guest'];
                        $data = DB::table('p_program_master')->where('id', $id)->update(['is_print' => 0, 'number_of_guest' => $guests]);
                    }
                }
                DB::commit();
                return sendJson('Program updated successfully', $information, 200);
            } catch (Exception $exp) {
                DB::rollBack();
                return response([
                    'message' => $exp->getMessage(),
                    'status' => 'failed'
                ], 400);
            }
        } else {
            return response()->json([
                "error" => "Oops!This program indent already comple"
            ]);
        }
     }
    public function update(Request $request, $id)
    {
        $dataprog['order_type_id'] = $request->order_type_id;
        $dataprog['number_of_guest'] = $request->number_of_guest;
        $information = OrderMaster::where('id', $id)->update($dataprog);
        return sendJson('Program updated successfully', $information, 200);
    }


    public function cardLastestID($program_id)
    {
        $latest = ModelsPProgramCard::select('id')->latest()->first();
        if ($latest) {
            $invoice = $latest->id;
        } else {
            $invoice = null;
        }
        $purchaseID = $this->invoiceNumber($latest, $invoice, "");
        return $purchaseID;
    }

    public function invoiceNumber($latest, $val, $pre)
    {
        if (!$latest) {
            return $pre . '1';
        }
        $string = preg_replace("/[^0-9\.]/", '', $val);
        return  $pre . $string + 1;
    }

    public function updateProgramMenu($information)
    {
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * program initial data
     */

    public function programMenuget()
    {
        // menu id
        $menu = DB::table('var_item_info')->join('5m_sv_uom', 'var_item_info.uom_id', '=', '5m_sv_uom.id')
            ->select(
                'var_item_info.id',
                'var_item_info.prod_type_id',
                'var_item_info.current_rate',
                'var_item_info.display_itm_name',
                '5m_sv_uom.id as uom_id',
                '5m_sv_uom.uom_short_code'
            )->where('var_item_info.is_active', 1)->get();
        $collection = collect($menu);

        // program menu
        $menuitem = $collection->where('prod_type_id', 3);
        $result = [];
        $m = 0;
        foreach ($menuitem as $item => $key) {
            $result[$m]['id'] = $key->id;
            $result[$m]['prod_type_id'] = $key->prod_type_id;
            $result[$m]['current_rate'] = $key->current_rate;
            $result[$m]['display_itm_name'] = $key->display_itm_name;
            $result[$m]['uom_id'] = $key->uom_id;
            $result[$m]['uom_short_code'] = $key->uom_short_code;
            $m++;
        }

        // ride
        $ride = $collection->where('prod_type_id', 10);
        $rideResult = [];
        $k = 0;
        foreach ($ride as $item => $key) {
            $rideResult[$k]['id'] = $key->id;
            $rideResult[$k]['prod_type_id'] = $key->prod_type_id;
            $rideResult[$k]['current_rate'] = $key->current_rate;
            $rideResult[$k]['display_itm_name'] = $key->display_itm_name;
            $rideResult[$k]['uom_id'] = $key->uom_id;
            $rideResult[$k]['uom_short_code'] = $key->uom_short_code;
            $k++;
        }

        // services
        $service = $collection->where('prod_type_id', 8);
        $serviceResult = [];
        $kk = 0;
        foreach ($service as $item => $key) {
            $serviceResult[$kk]['id'] = $key->id;
            $serviceResult[$kk]['prod_type_id'] = $key->prod_type_id;
            $serviceResult[$kk]['current_rate'] = $key->current_rate;
            $serviceResult[$kk]['display_itm_name'] = $key->display_itm_name;
            $serviceResult[$kk]['uom_id'] = $key->uom_id;
            $serviceResult[$kk]['uom_short_code'] = $key->uom_short_code;
            $kk++;
        }
        return response()->json([
            'menu' => $result,
            'ride' => $rideResult,
            'serviece' => $serviceResult,
        ]);
    }

    public function initialize()
    {
        // hall room
        $hall_room = DB::table('var_restaurant_floor')->select('id', 'floor_name')->WHERE('is_active', 1)->get();
        // customer
        $customer = DB::table('cs_customer_details')
            ->leftJoin('cs_customer_contact_info', 'cs_customer_details.id', 'cs_customer_contact_info.customer_id')
            ->select('cs_customer_details.id', 'customer_name', 'contact_person')->where('cs_customer_contact_info.is_active', 1)->get();
        //program type
        $program_type = DB::table('5z2_program_type')->select('id', 'program_type_name')->where('is_active', 1)->get();
        // program session
        $prog_session = ProgramSession::all();




        $cus_id = $this->customerID();
        $card_id = $this->cardID();
        return response()->json([
            'hall_room' => $hall_room,
            'customer' => $customer,
            'prog_type' => $program_type,
            'cus_id' => $cus_id,
            'card' => $card_id,
            'prog_session' => $prog_session,
        ]);
    }

    // card id
    public function cardID()
    {
        $latest = DB::table('var_program_card')->latest()->select('card_id')->first();
        if ($latest) {
            $invoice = $latest->card_id;
        } else {
            $invoice = null;
        }
        $idgen = new IdgenerateHelper();
        $cusID = $idgen->invoiceNumber($latest, $invoice);
        return $cusID;
    }
    public function customerID()
    {
        $latest = DB::table('cs_customer_details')->latest()->select('id')->first();
        if ($latest) {
            $invoice = $latest->id;
        } else {
            $invoice = null;
        }
        $idgen = new IdgenerateHelper();
        $cusID = $idgen->invoiceNumber($latest, $invoice);
        return $cusID;
    }

    // program menu add
    private function programMenu($program_menu)
    {
    }

    // hasprogram

    public function hasProgram($start, $end, $floor)
    {
        $prog = Carbon::parse($start)->format('Y-m-d');
        // $prog=date("Y-m-d", strtotime($start));
        return PProgramMaster::where('program_date', $prog)->where('program_session_id', $end)->where('floor_id', $floor)->count();
    }

    // finished program

    public function finishedProg()
    {
        $search = request('search', '');
        $perPage = request('perPage', 10);

        return OrderMaster::select(
            'trns00g_order_master.*',
            'sl_name as floor_name',
            'customer_name',
            'customer_phone as phone_number',
            // 'program_pay_details.due_amount',
            'var_program_sessions.session_name',
            'var_program_sessions.start_time',
            'var_program_sessions.end_time',
        )
        ->leftJoin('cs_company_store_location','cs_company_store_location.id','trns00g_order_master.floor_id')
        ->leftJoin('cs_customer_details','cs_customer_details.id','trns00g_order_master.customer_id')
        ->leftJoin('var_program_sessions','var_program_sessions.id','trns00g_order_master.program_session_id')
            ->where('program_date', '<', date('Y-m-d'))->where('program_name', 'like', "%{$search}%")
            ->orderBy('program_date', 'DESC')
            ->paginate($perPage);
    }

    public function checkProgram(Request $request)
    {
        $card_id = $request->card_no;

        return DB::table("p_program_card")
            ->select(
                'p_program_card.program_master_id',
                "p_program_card.card_id",
                'p_program_card.use_status',
                'p_program_master.program_name',
                'p_program_master.program_name_bn',
                'p_program_master.prog_start_time',
                'p_program_master.prog_end_time',
                'p_program_master.is_active',
                'p_program_master.program_name',
                DB::raw("(CASE
                    WHEN NOW() BETWEEN p_program_master.prog_start_time AND p_program_master.prog_end_time THEN 'running'
                    WHEN NOW() < p_program_master.prog_start_time THEN 'upcoming'
                    WHEN NOW() > p_program_master.prog_end_time THEN 'closed'
                END) AS prog_status")
            )
            ->leftjoin('p_program_master', 'p_program_card.program_master_id', '=', 'p_program_master.id')
            ->where('p_program_card.card_id', $card_id)
            ->first();
    }
    public function storeWiseItem(){
        $floorId = request()->input('floor_id');

        // varItemInfo() is the helper function for the var item info

        $ride = varItemInfo()->where('var_item_sub_group.id', 44)->get();
        $serviece = varItemInfo()->where('5h_sv_product_category.id', 2)->get();
        $menu = varItemInfo()->where('r_floor.id',$floorId)
                        ->where('5f_sv_product_type.id',3)
                        ->get();
        return response()->json([
            'menu'=>$menu,
            'ride'=>$ride,
            'serviece'=>$serviece
        ]);
    }
}
