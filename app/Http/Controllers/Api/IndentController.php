<?php

namespace App\Http\Controllers\Api;

use Throwable;
use Carbon\Carbon;
use App\Models\SvUOM;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\IndentChildren;
use App\Models\ItemChildModel;
use App\Models\PProgramMaster;
use App\Models\CsEmployeeModel;
use App\Models\ItemMasterModel;
use Dflydev\DotAccessData\Data;
use App\Models\IsseIndentMaster;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\IndentResource;
use Illuminate\Database\QueryException;
use App\Http\Requests\ItemIndentRequest;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Requests\KitchenSubStoreRequest;

class IndentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    public function index()
    {
        $indent = ItemMasterModel::with('item_indent_child', 'item_indent_child.itemInfo', 'programs')->get();
        if ($indent->count() > 0) {
            return response()->json([
                'success' => 'success',
                'data' => $indent,
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'failed',
                'data' => $indent,
                'status' => 'failed',
            ]);
        }
    }

    /**
     * create index num ber
     */
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
        $validated = Validator::make($request->all(), [
            'indent_date' => 'required|date',
            'program_master_id' => 'required|numeric',
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
            $grnNumber = DB::select('CALL getTableID("trns00a_indent_master","' . $company_id . '")');
            $indentmaster = [
                'indent_number' => $grnNumber[0]->masterID,
                'program_master_id' => $request['program_master_id'],
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
            $data = [$indentStoredMaster, $indentChildStored];
            return response([
                'message' => 'Data stored success',
                'data' => $data,
            ], 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return response([
                'message' => $th->getMessage(),
                'status' => 'failed'
            ], 400);
        }
    }
    /**
     * create indent child
     */
    public function createIndentChild($data)
    {
        $validator = Validator::make($data, [
            'program_master_id'  => 'required',
            'indent_master_id' => 'required',
            'item_information_id'  => 'required',
            'uom_id'  => 'required',
            'uom_short_code'  => 'required',
            'relative_factor'  => 'required',
            'indent_quantity' => 'required',
            'consum_order_qty' => 'required',
            'Remarks' => 'nullable',
            'required_date' => 'nullable',
            'created_at'  => 'required',
            'updated_at'  => 'required',
            'created_by'  => 'nullable',
            'updated_by' => 'nullable'
        ]);
        // if( !$validator->required_date){
        //     $validator['required_date']=$data['indent_datete'];
        // }

        return ItemChildModel::create($validator->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = DB::table('p_program_master')
                ->leftJoin('cs_company_store_location', 'p_program_master.restaurant_master_id', '=', 'cs_company_store_location.store_id')
                ->leftJoin('r_floor', 'p_program_master.floor_id', '=', 'r_floor.id')
                ->leftJoin('cs_customer_details', 'p_program_master.customer_id', '=', 'cs_customer_details.id')
                ->leftJoin('r_program_type', 'p_program_master.program_type_id', '=', 'r_program_type.id')
                ->leftJoin('p_program_menu', 'p_program_master.id', '=', 'p_program_menu.program_master_id')
                ->leftJoin('p_program_card', 'p_program_master.id', '=', 'p_program_card.program_master_id')
                ->leftJoin('var_item_info', 'p_program_menu.menu_id', '=', 'var_item_info.item_information_id')
                ->leftJoin('5m_sv_uom', 'var_item_info.uom_id', '=', '5m_sv_uom.uom_id')
                ->leftJoin('users', 'p_program_master.created_by', '=', 'users.id')
                ->leftJoin('program_pay_details', 'p_program_master.id', '=', 'program_pay_details.p_program_master_id')
                // ->leftJoin('r_card','p_program_card.card_id','=','r_card.id')
                ->leftJoin('tran01a_ioc_price_declaration', 'tran01a_ioc_price_declaration.item_information_id', '=', 'p_program_menu.menu_id')
                ->leftJoin('tran01b_ioc_item_details', 'tran01a_ioc_price_declaration.ioc_price_declaration_id', '=', 'tran01b_ioc_item_details.ioc_price_declaration_id')
                //
                ->select(
                    'p_program_master.*',
                    'cs_company_store_location.sl_name',
                    'cs_company_store_location.sl_name_bn',
                    'r_floor.floor_name',
                    'r_floor.floor_name_bn',
                    'cs_customer_details.customer_name',
                    'cs_customer_details.phone_number',
                    'cs_customer_details.customer_name_bn',
                    'r_program_type.program_type_name',
                    'r_program_type.program_type_name_bn',
                    'p_program_menu.id AS p_menu_id',
                    'p_program_menu.menu_id AS menu_id',
                    'p_program_menu.menu_qty AS menu_qty',
                    'p_program_menu.prod_type_id',
                    'p_program_menu.menu_rate AS menu_rate',
                    'p_program_menu.menu_amount AS menu_amount',
                    'var_item_info.display_itm_name',
                    'var_item_info.display_itm_name_bn',
                    'var_item_info.prod_type_id',
                    '5m_sv_uom.uom_id',
                    '5m_sv_uom.uom_short_code',
                    '5m_sv_uom.relative_factor',
                    'p_program_card.id AS p_card_id',
                    'p_program_card.card_id AS card_id',
                    'p_program_card.use_status AS use_status',
                    'program_pay_details.total_amount',
                    'program_pay_details.paid_amount',
                    'program_pay_details.due_amount',
                    'users.name AS username',
                    'tran01b_ioc_item_details.ioc_price_declaration_id AS ic_pc'

                )
                ->where('p_program_master.id', $id)
                ->where('p_program_menu.prod_type_id', 3)->get();
            $Data = collect($data);
            $result = [];
            $i = 0;
            foreach ($data as $i => $item) {
                if ($i == 0) {
                    $result['program_master_id'] = $item->id;
                    $result['indent_number'] = $this->indentNumber();
                    $result['store_id'] = $item->restaurant_master_id;
                    $result['sl_name'] = $item->sl_name;
                    $result['sl_name_bn'] = $item->sl_name_bn;
                    // floor
                    $result['floor_id'] = $item->floor_id;
                    $result['floor_id'] = $item->floor_id;

                    // floor
                    $result['total_amount_without_vat'] = $item->total_amount_without_vat;
                    $result['vat_on_food'] = $item->vat_on_food;
                    $result['food_vat_per'] = $item->food_vat_per;
                    $result['vat_on_food'] = $item->vat_on_food;
                    $result['username'] = $item->username;
                    $result['paid_amount'] = $item->paid_amount;
                    $result['remarks'] = $item->remarks;
                    $result['due_amount'] = $item->due_amount;
                    $result['hall_room_charge_vat_per'] = $item->hall_room_charge_vat_per;

                    //
                    $result['prod_type_id'] = $item->prod_type_id;

                    $result['floor_name'] = $item->floor_name;
                    $result['floor_name_bn'] = $item->floor_name_bn;
                    $result['customer_id'] = $item->customer_id;
                    $result['customer_name'] = $item->customer_name;
                    $result['phone'] = "0" . $item->phone_number;
                    $result['customer_name_bn'] = $item->customer_name_bn;
                    $result['program_type_id'] = $item->program_type_id;
                    $result['program_type_name'] = $item->program_type_name;
                    $result['program_type_name_bn'] = $item->program_type_name_bn;
                    $result['program_name'] = $item->program_name;
                    $result['program_name_bn'] = $item->program_name_bn;
                    $result['prog_date'] = $item->prog_date;
                    $result['prog_start_time'] = $item->prog_start_time;
                    $result['prog_end_time'] = $item->prog_end_time;
                    $result['number_of_guest'] = $item->number_of_guest;
                    $result['hall_room_charge'] = $item->hall_room_charge;
                    $result['hall_room_vat'] = $item->hall_room_vat;
                    $result['food_charge'] = $item->food_charge;
                    $result['total_amount'] = $item->total_amount;
                    $result['vat_amount'] = $item->vat_amount;
                    $result['total_amount_with_vat'] = $item->total_amount_with_vat;
                    $result['is_active'] = $item->is_active;
                    $result['is_print'] = $item->is_print;
                    $result['created_at'] = $item->created_at;
                    $result['updated_at'] = $item->updated_at;
                    $result['created_by'] = $item->created_by;
                    $result['updated_by'] = $item->updated_by;

                    $menuData = $Data->where('id', $item->id)->groupBy('p_menu_id');
                    $m = 0;
                    foreach ($menuData as $itm => $itmInfo) {
                        $result['program_childs'][$m]['p_menu_id'] = $itmInfo[0]->p_menu_id;
                        $result['program_childs'][$m]['menu_id'] = $itmInfo[0]->menu_id;
                        $result['program_childs'][$m]['menu_name'] = $itmInfo[0]->display_itm_name;
                        $result['program_childs'][$m]['menu_name_bn'] = $itmInfo[0]->display_itm_name_bn;
                        $result['program_childs'][$m]['menu_qty'] = $itmInfo[0]->menu_qty;
                        $result['program_childs'][$m]['menu_rate'] = $itmInfo[0]->menu_rate;
                        $result['program_childs'][$m]['menu_amount'] = $itmInfo[0]->menu_amount;
                        $result['program_childs'][$m]['uom_id'] = $itmInfo[0]->uom_id;
                        $result['program_childs'][$m]['uom_short_code'] = $itmInfo[0]->uom_short_code;
                        $result['program_childs'][$m]['uom'] = $itmInfo[0]->uom_short_code;
                        $result['program_childs'][$m]['relative_factor'] = $itmInfo[0]->relative_factor;
                        $m++;
                    }
                    $cardData = $Data->where('id', $item->id)->groupBy('p_card_id');
                    $k = 0;
                    foreach ($cardData as $itm => $itmInfo) {
                        $result['card_item'][$k]['p_card_id'] = $itmInfo[0]->p_card_id;
                        $result['card_item'][$k]['card_id'] = $itmInfo[0]->card_id;
                        $result['card_item'][$k]['use_status'] = $itmInfo[0]->use_status;
                        // $result['card_item'][$k]['card_number'] = $itmInfo[0]->card_number;
                        $k++;
                        // paid_amount
                    }
                }
            }


            $programdData = DB::table('p_program_menu')
                ->leftJoin('tran01a_ioc_price_declaration', 'tran01a_ioc_price_declaration.item_information_id', '=', 'p_program_menu.menu_id')
                ->leftJoin('tran01b_ioc_item_details', 'tran01a_ioc_price_declaration.ioc_price_declaration_id', '=', 'tran01b_ioc_item_details.ioc_price_declaration_id')
                ->leftJoin('var_item_info', 'tran01b_ioc_item_details.item_information_id', '=', 'var_item_info.item_information_id')
                ->leftJoin('5m_sv_uom', 'var_item_info.uom_id', '=', '5m_sv_uom.uom_id')
                ->select(
                    'var_item_info.item_information_id',
                    'var_item_info.display_itm_name',
                    'tran01b_ioc_item_details.consumption',
                    DB::raw('ROUND(sum(p_program_menu.menu_qty * tran01b_ioc_item_details.consumption),3) AS indent_quantity '),
                    'var_item_info.uom_id',
                    'uom_short_code',
                    DB::raw('ROUND(sum(p_program_menu.menu_qty * tran01b_ioc_item_details.consumption),3) as consum_order_qty')
                )->where('p_program_menu.program_master_id', $id)->groupBy('var_item_info.item_information_id')->get();
            $ii = 0;
            foreach ($programdData as $itm => $itmInfo) {
                if ($itmInfo->item_information_id != null) {
                    $result['indent_child'][$ii]['item_information_id'] = $itmInfo->item_information_id;
                    $result['indent_child'][$ii]['display_itm_name'] = $itmInfo->display_itm_name;
                    $result['indent_child'][$ii]['consumption'] = $itmInfo->consumption;
                    $result['indent_child'][$ii]['indent_quantity'] = $itmInfo->indent_quantity;
                    $result['indent_child'][$ii]['uom_id'] = $itmInfo->uom_id;
                    $result['indent_child'][$ii]['uom_short_code'] = $itmInfo->uom_short_code;
                    $result['indent_child'][$ii]['consum_order_qty'] = $itmInfo->consum_order_qty;
                    $result['indent_child'][$ii]['required_date'] = date("Y-m-d");
                    $ii++;
                }
            }

            return $result;
        } catch (QueryException $ex) {
            return response()->json([
                "dd" => $ex->getMessage()
            ]);
        }
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
        //
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
    // get all Indent
    public function indentall()
    {
        $search = request('search', '');
        $perPage = request('perPage', 10);
        return IndentResource::collection(ItemMasterModel::join(
            'p_program_master',
            'p_program_master.id',
            '=',
            'trns00a_indent_master.program_master_id'
        )
            ->leftjoin('cs_employee_master', 'trns00a_indent_master.submitted_by', '=', 'cs_employee_master.id')
            ->select('trns00a_indent_master.*', 'trns00a_indent_master.id AS idd', 'emp_name', 'p_program_master.prog_date', 'p_program_master.program_name')
            ->where('indent_number', 'like', "%{$search}%")
            ->orWhere('p_program_master.program_name', 'like', "%{$search}%")
            ->orderBy('id', 'DESC')
            ->paginate($perPage));
    }


    public function readOnePrint($id)
    {
        try {
            $data = DB::table('trns00a_indent_master')
                ->leftJoin('p_program_master', 'p_program_master.id', '=', 'trns00a_indent_master.program_master_id')
                ->leftJoin('trns00b_indent_child', 'trns00a_indent_master.id', '=', 'trns00b_indent_child.indent_master_id')
                ->leftjoin('var_item_info', 'trns00b_indent_child.item_information_id', '=', 'var_item_info.item_information_id')
                ->leftJoin('cs_employee_master', 'cs_employee_master.employee_id', '=', 'trns00a_indent_master.submitted_by')
                ->leftJoin('program_sessions', 'p_program_master.program_session_id', '=', 'program_sessions.id')
                ->leftJoin('r_floor', 'r_floor.id', '=', 'p_program_master.floor_id')
                ->select(
                    'trns00a_indent_master.*',
                    'trns00b_indent_child.*',
                    'trns00a_indent_master.id As iid',
                    'var_item_info.display_itm_name',
                    'var_item_info.display_itm_name_bn',
                    'emp_name',
                    'floor_name',
                    'p_program_master.program_name',
                    'p_program_master.number_of_guest',
                    'p_program_master.prog_start_time',
                    'p_program_master.prog_end_time',
                    'p_program_master.prog_date',
                    'var_item_info.current_rate',
                    'program_sessions.session_name',
                    'program_sessions.start_time',
                    'program_sessions.end_time'
                )

                ->where('trns00a_indent_master.id', $id)
                ->get();

            $result = [];
            $i = 0;
            foreach ($data as $i => $item) {
                if ($i == 0) {
                    $result['id'] = $item->iid;
                    $result['indent_master_id'] = $item->indent_master_id;
                    $result['indent_number'] = $item->indent_number;
                    $result['floor_name'] = $item->floor_name;
                    $result['prod_type_id'] = $item->prod_type_id;
                    $result['company_id'] = $item->company_id;
                    $result['branch_id'] = $item->branch_id;
                    $result['emp_name'] = $item->emp_name;
                    $result['store_id'] = $item->store_id;
                    $result['indent_date'] = $item->indent_date;

                    $result['program_name'] = $item->program_name;
                    $result['start_time'] = $item->start_time;
                    $result['end_time'] = $item->end_time;
                    $result['session_name'] = $item->session_name;
                    $result['number_of_guest'] = $item->number_of_guest;
                    $result['prog_date'] = $item->prog_date;

                    $result['remarks'] = $item->remarks;
                    $result['remarks_bn'] = $item->remarks_bn;
                    $result['submitted_by'] = $item->submitted_by;
                    $result['recommended_by'] = $item->recommended_by;
                    $result['approved_by'] = $item->approved_by;
                    $result['approved_status'] = $item->approved_status;
                    $result['created_at'] = $item->created_at;
                    $result['updated_at'] = $item->updated_at;
                    $result['created_by'] = $item->created_by;
                    $result['updated_by'] = $item->updated_by;
                    $result['printQr'] = strval(QrCode::size(100)->generate('Program: ' . $item->program_name . 'Indent ID:' . $item->iid . 'Date :' . $item->prog_start_time . '-' . $item->prog_end_time));
                    $result['item_row'] = [];
                }
                $result['item_row'][$i]['indent_child_id'] = $item->indent_child_id;
                $result['item_row'][$i]['item_information_id'] = $item->item_information_id;
                $result['item_row'][$i]['display_itm_name'] = $item->display_itm_name;
                $result['item_row'][$i]['display_itm_name_bn'] = $item->display_itm_name_bn;
                $result['item_row'][$i]['price'] = $item->current_rate;
                $result['item_row'][$i]['uom_id'] = $item->uom_id;
                $result['item_row'][$i]['uom_short_code'] = $item->uom_short_code;
                $result['item_row'][$i]['indent_quantity'] = $item->indent_quantity;
                $result['item_row'][$i]['issue_qty'] = $item->indent_quantity;
                $result['item_row'][$i]['consum_order_qty'] = $item->consum_order_qty;
                $result['item_row'][$i]['Remarks'] = $item->Remarks;
                $result['item_row'][$i]['required_date'] = $item->required_date;

                $result['item_row'][$i]['lineTotal'] = $item->indent_quantity * $item->current_rate;
            }
            $menuData = DB::table('trns00a_indent_master')
                ->leftJoin('p_program_menu', 'trns00a_indent_master.program_master_id', '=', 'p_program_menu.program_master_id')
                ->leftJoin('var_item_info', 'p_program_menu.menu_id', '=', 'var_item_info.item_information_id')
                ->leftJoin('5m_sv_uom', 'var_item_info.uom_id', '=', '5m_sv_uom.uom_id')
                ->select(
                    'p_program_menu.id AS p_menu_id',
                    'p_program_menu.menu_id AS menu_id',
                    'p_program_menu.menu_qty AS menu_qty',
                    'p_program_menu.menu_rate AS menu_rate',
                    'p_program_menu.menu_amount AS menu_amount',
                    'p_program_menu.prod_type_id',
                    'var_item_info.display_itm_name',
                    'var_item_info.display_itm_name_bn',
                    'var_item_info.prod_type_id',
                    '5m_sv_uom.uom_id',
                    '5m_sv_uom.uom_short_code',
                    '5m_sv_uom.relative_factor',

                )->where('trns00a_indent_master.id', $id)->where('p_program_menu.prod_type_id', 3)
                ->get();

            $m = 0;
            foreach ($menuData as $itm => $itmInfo) {
                $result['program_childs'][$m]['p_menu_id'] = $itmInfo->p_menu_id;
                $result['program_childs'][$m]['menu_id'] = $itmInfo->menu_id;
                $result['program_childs'][$m]['menu_name'] = $itmInfo->display_itm_name;
                $result['program_childs'][$m]['menu_name_bn'] = $itmInfo->display_itm_name_bn;
                $result['program_childs'][$m]['menu_qty'] = $itmInfo->menu_qty;
                $result['program_childs'][$m]['menu_rate'] = $itmInfo->menu_rate;
                $result['program_childs'][$m]['prod_type_id'] = $itmInfo->prod_type_id;
                $result['program_childs'][$m]['menu_amount'] = $itmInfo->menu_amount;
                $result['program_childs'][$m]['uom_id'] = $itmInfo->uom_id;
                $result['program_childs'][$m]['uom_short_code'] = $itmInfo->uom_short_code;
                $result['program_childs'][$m]['uom'] = $itmInfo->uom_short_code;
                $result['program_childs'][$m]['relative_factor'] = $itmInfo->relative_factor;
                $m++;
            }

            return $result;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 404,
                'message' => $e->getMessage()
            ]);
        }
    }



    public function indentNumbers()
    {

        $data = DB::table('trns00a_indent_master')
            ->where('store_id', auth()->user()->store_id)
            ->select('id', 'indent_number', 'indent_date')->get();
        return response()->json($data, 200);
    }



    public function getItems($masterId)
    {
        return DB::table('trns00b_indent_child as indentChild')
            ->leftJoin('var_item_info as product', 'indentChild.item_information_id', '=', 'product.item_information_id')
            ->where('indent_master_id', $masterId)
            ->select(
                'indentChild.indent_child_id',
                'indentChild.uom_short_code',
                'indentChild.indent_quantity',
                'product.display_itm_name as item_name',
            )
            ->get();
    }




    public function storeKitchenToSubstore(KitchenSubStoreRequest $request)
    {
        $data   = $request->validated();

        try {
            DB::beginTransaction();
            $master = ItemMasterModel::create($data);
            $children = [];
            foreach ($data['children'] as $item) {
                $item['indent_master_id'] = $master->id;
                $children[] = $item;
            }
            ItemChildModel::insert($children);

            DB::commit();

            return response("successfully indent created", 200);
        } catch (\Exception $error) {
            DB::rollBack();
            return response()->json(['message' => $error->getMessage()], 500);
        }
    }
}