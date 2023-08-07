<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderMaster;
use Throwable;
use App\Helpers\Helper;
use App\Models\Currency;
use App\Models\IssueChild;
use App\Models\IssueMaster;
use App\Models\IndentMaster;
use Illuminate\Http\Request;
use App\Models\ItemChildModel;
use App\Models\ItemStockChild;
use App\Models\TransferMaster;
use App\Models\ItemMasterModel;
use App\Models\IsseIndentMaster;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\TransactionSourceType;
use App\Models\ToutProductReqQuantity;
use Illuminate\Database\QueryException;

class IssurRMController extends Controller
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
        return IssueMaster::with('indentMaster', 'indentMaster.programs')
        ->where('indent_master_id','!=',null)
        ->orderBy('created_at', 'DESC')->paginate($perPage);
        // return sendJson('Issue RM List', $issueMasterList, 200);
    }

    /**
     * initialize data for Issue raw material interface
     */
    public function init()
    {
        $search = request()->input('search');
        $perPage = request()->input('perPage', 10);
        return ItemMasterModel::leftJoin('trns00g_order_master',
         'trns00g_order_master.id', '=', 'trns00a_indent_master.program_master_id')
         ->select('trns00a_indent_master.id','program_master_id','indent_number','indent_date','program_date','program_name','number_of_guest')
            ->where('close_status', 0)
            ->where('product_req',0)
            ->where('program_master_id', '!=', 0)
            ->where('program_name', 'like', "%{$search}%")
            ->paginate($perPage);
    }
    public function initRmIssueOne($id)
    {
        $programsList = ItemMasterModel::with(
            'programs',
            'item_indent_child',
            'item_indent_child.itemInfo'
        )
            ->find($id);
        if ($programsList) {
            $childDataList = [];
            $i = 0;
            foreach ($programsList->item_indent_child as $index => $data) {
                $childDataList[$i]['indent_child_id'] = $data->id;
                $childDataList[$i]['indent_master_id'] = $data->indent_master_id;
                $childDataList[$i]['item_information_id'] = $data->item_information_id;
                $childDataList[$i]['uom_id'] = $data->uom_id;
                $childDataList[$i]['item_rate'] = $data->itemInfo->ioc_rate;
                $childDataList[$i]['display_item_name'] = $data->itemInfo->display_item_name;
                $childDataList[$i]['uom_short_code'] = $data->uom_short_code;
                $childDataList[$i]['relative_factor'] = $data->relative_factor;
                $item = ItemStockChild::find($data->item_information_id);
                if ($item) {
                    $childDataList[$i]['balance_quantity'] = $item->opening_bal_qty;
                } else {
                    $childDataList[$i]['balance_quantity'] = 0;
                }
                $childDataList[$i]['indent_quantity'] = $data->indent_quantity;
                $childDataList[$i]['consum_order_qty'] = $data->consum_order_qty;
                $childDataList[$i]['required_date'] = $data->required_date;
                $childDataList[$i]['Remarks'] = "";
                $childDataList[$i]['created_by'] = $data->created_by;
                $childDataList[$i]['updated_by'] = $data->updated_by;
                $i++;
            }
            $data = [
                'id' => $programsList->id,
                'program_master_id' => $programsList->program_master_id,
                'program_name' => $programsList->programs->program_name,
                'prog_date' => $programsList->programs->prog_date,
                'number_of_guest' => $programsList->programs->number_of_guest,
                'indent_number' => $programsList->indent_number,
                'indent_date' => $programsList->indent_date,
                'remarks' => $programsList->remarks,
                'item_row' => $childDataList,
            ];
        }
        if ($programsList) {
            return sendJson('Program Loaded', $data, 200);
        } else {
            return sendJson('Programs Not Found', [], 400);
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
        $comID = Auth::user()->company_id;
        $data = DB::select('CALL getTableID("trns03a_issue_master","' . $comID . '")');
        $issueMasterID = $data[0]->masterID;
        $branch_id = auth()->user()->branch_id;
        $created_by = auth()->user()->id;
        $current_month = date('m');
        $company_id = auth()->user()->company_id;
        $store_id = auth()->user()->store_id;
        $fiscal = fiscalYearAndMonth($request->issue_date);
        try {
            DB::beginTransaction();
            $issueMasterData = [
                'indent_master_id' => $request->indent_master_id,
                'branch_id' => $branch_id,
                'tran_type_id' => TransactionSourceType::where('tran_source_type_name', 'like', 'Issue')->first()->id,
                'created_by' => $created_by,
                'company_id' => $company_id,
                'store_id' => $store_id,
                'fiscal_year' => $fiscal['fiscal_year'],
                'vat_month' => $fiscal['vat_month'],
                'issue_number' =>  $issueMasterID,
                'issue_number_bn' =>  $issueMasterID,
                'issue_date' => date('Y-m-d',strtotime($request->issue_date)) ,
                'requisition_num' =>  $issueMasterID,
                'requisition_num_bn' =>  $issueMasterID,
                'remarks' => $request->remarks,
                'remarks_bn' => $request->remarks,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'delivery_to' => $request->to_store_id,
                'delivery_to_bn' => $request->to_store_id,
                'currency_id' => Currency::where('currency_shortcode', 'like', 'BDT')->first()->id,
            ];
            $isseuMaster = IssueMaster::create($issueMasterData);
            $issueChildData = [];
            foreach ($request->item_row as $item) {
                $issueChildDatas = [
                    'issue_master_id' => $isseuMaster->id,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'item_information_id' => $item['item_information_id'],
                    'uom_id' => $item['uom_id'],
                    'indent_child_id' => $item['indent_child_id'],
                    'item_rate' => $item['rate'],
                    'uom_short_code' => $item['uom_short_code'],
                    'relative_factor' => 1,
                    'issue_qty' => $item['issue_quantity'],
                    'issue_qty_adjt' => $item['issue_quantity'],
                ];
                array_push($issueChildData, $issueChildDatas);
            }
            IssueChild::insert($issueChildData);
            
            DB::commit();
            return Helper::sendJson(arr: [
                "message" => 'Issued to production',
                "data" => $isseuMaster,
            ]);
        } catch (Throwable $th) {
            return Helper::sendJson(arr: [
                "message" => $th->getMessage(),
                "data" => [],
            ]);
        }
    }
    public function updateStatusIssue($id){

        return ItemMasterModel::where('id', $id)->update(['issue_status' => 1]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = ItemChildModel::leftJoin('trns03b_issue_child', 'trns03b_issue_child.indent_child_id', '=', 'trns00b_indent_child.id')
            ->leftJoin('var_item_info', 'trns00b_indent_child.item_information_id', '=', 'var_item_info.id')
            ->leftJoin('5m_sv_uom', 'var_item_info.uom_id', '=', '5m_sv_uom.id')
            ->select(
                'trns00b_indent_child.id as indent_child_id',
                'trns00b_indent_child.uom_short_code',
                'trns00b_indent_child.required_date',
                'trns00b_indent_child.uom_id',
                'trns00b_indent_child.indent_master_id',
                'trns00b_indent_child.item_information_id',
                'trns00b_indent_child.indent_quantity',
                DB::raw('ROUND(trns00b_indent_child.indent_quantity,3) as indent_quantity'),
                DB::raw('ROUND(trns00b_indent_child.indent_quantity,3) as issue_quantity'),
                DB::raw('ROUND((ioc_rate * trns00b_indent_child.indent_quantity),3) AS lineTotal'),
                DB::raw('ROUND(sum(trns03b_issue_child.issue_qty),3) AS pre_issue_quantity'),
                'ioc_rate as rate',
                'var_item_info.display_itm_name',
                'var_item_info.display_itm_name_bn',
                'trns03b_issue_child.issue_qty AS PP',
                'var_item_info.prod_type_id',
                '5m_sv_uom.id as uom_id',
                '5m_sv_uom.uom_short_code',
                '5m_sv_uom.relative_factor',
            )->where('trns00b_indent_child.indent_master_id', $id)
            ->groupBy('trns00b_indent_child.item_information_id')
            ->get();
        $result = [];
        $i = 0;
        foreach ($data as $key => $item) {
            if ($item->indent_quantity != $item->pre_issue_quantity) {
                $result[$i]['indent_master_id'] = $item->indent_master_id;
                $result[$i]['indent_child_id'] = $item->indent_child_id;
                $result[$i]['item_information_id'] = $item->item_information_id;
                $result[$i]['uom_id'] = $item->uom_id;
                $result[$i]['uom_short_code'] = $item->uom_short_code;
                $result[$i]['indent_quantity'] = $item->indent_quantity;
                $result[$i]['issue_quantity'] = $item->issue_quantity;
                if ($item->pre_issue_quantity) {
                    $result[$i]['pre_issue_quantity'] = $item->pre_issue_quantity;
                    $result[$i]['issue_quantity'] = number_format((float)$item->indent_quantity - $item->pre_issue_quantity, 3, '.', '');
                    $result[$i]['lineTotal'] = (float) $item->rate * ($item->indent_quantity - $item->pre_issue_quantity);
                } else {
                    $result[$i]['pre_issue_quantity'] = 0;
                    $result[$i]['issue_quantity'] = (float) $item->issue_quantity;
                    $result[$i]['lineTotal'] = (float) $item->rate * $item->issue_quantity;
                }
                $result[$i]['rate'] = $item->rate;
                $result[$i]['balance_qty'] = 0.00;
                $result[$i]['display_itm_name'] = $item->display_itm_name;
                $result[$i]['display_itm_name_bn'] = $item->display_itm_name_bn;
                $result[$i]['prod_type_id'] = $item->prod_type_id;
                $result[$i]['required_date'] = date('d-m-Y', strtotime($item->required_date));
                $i++;
            }
        }
        return $result;
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
    public function ReadAllIndentItem()
    {
        try {

            return DB::table('trns00a_indent_master')->get();
        } catch (\Exception $e) {
            return  dd(404, $e->getMessage());
        }
    }

    public function PrintDataApi($id)
    {
        $data['orderItem'] = json_encode($this->ReadOneIssueRm($id));
        $data['indentList'] = json_encode($this->ReadAllIndentItem());
        return $data;
    }

    public function ReadOneIssueRm($id)
    {
        try {
            $data =  IssueMaster::with('issueChild','store', 'issueChild.itemInfo','indentMaster', 'indentMaster.programs')
                ->find($id);
            
                // return response()->json($data->indentMaster);
            $result = [];
            $i = 0;
            foreach ($data->issueChild as $index => $res) {
                $result[$i]['issue_child_id'] = $res['id'];
                $result[$i]['issue_master_id'] = $res['issue_master_id'];
                $result[$i]['item_information_id'] = $res['item_information_id'];
                $result[$i]['uom_id'] = $res['uom_id'];
                $result[$i]['uom_short_code'] = $res['uom_short_code'];
                $result[$i]['required_date'] = $res['required_date'];
                $result[$i]['Remarks'] = $res['Remarks'];
                $result[$i]['item_rate'] = $res->itemInfo->ioc_rate;
                $result[$i]['issue_qty'] = $res['issue_qty'];
                $result[$i]['display_itm_name'] = $res->itemInfo->display_itm_name;
                $i++;
            }
            $datas = [
                'issue_master_id' => $data->id,
                'issue_status' => $data->indentMaster->issue_status,
                'store_name' => $data->store->sl_name,
                'indent_master_id' => $data->indent_master_id,
                'program_name' => $data->indentMaster->programs->program_name,
                'program_date' => $data->indentMaster->programs->prog_date,
                'number_of_guest' => $data->indentMaster->programs->number_of_guest,
                'tran_source_type_id' => $data->tran_source_type_id,
                'tran_type_id' => $data->tran_type_id,
                'tran_sub_type_id' => $data->tran_sub_type_id,
                'prod_type_id' => $data->prod_type_id,
                'delivery_to' => $data->delivery_to,
                'fiscal_year' => $data->fiscal_year,
                'vat_month' => $data->vat_month,
                'issue_number' => $data->issue_number,
                'issue_date' => $data->issue_date,
                'requisition_num' => $data->requisition_num,
                'challan_number' => $data->challan_number,
                'challan_date' => $data->challan_date,
                'remarks' => $data->remarks,
                'item_row' => $result,
            ];
            return sendJson('single issue rm view', $datas, 200);
        } catch (QueryException $ex) {
            return  sendJson('not found', null, 400);
        }
    }
}