<?php

namespace App\Http\Controllers\Api;

use App\Models\RecvChild;
use App\Models\RecvMaster;
use App\Models\IssueMaster;
use App\Models\VarItemInfo;
use Illuminate\Http\Request;
use App\Models\CreditNoteChild;
use App\Models\CreditNoteMaster;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class IssueReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request()->query('search'); // search credit note by program name
        $paginate = request()->query('perPage', 10);
        $cns = CreditNoteMaster::with([
            'issueMaster',
            'issueMaster.indentMaster',
            'issueMaster.indentMaster.programs' => function ($query) {
                $query->select('*');
            }
        ])
            ->whereHas('issueMaster.indentMaster.programs', function ($query) use ($search) {
                $query->where('program_name', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($paginate);
        $datalist = [];
        $i = 0;
        foreach ($cns as $index => $cn) {
            $datalist[$i]['issue_return_id'] = $cn->id;
            $datalist[$i]['return_date'] = $cn->created_at->format('d-m-Y');
            $datalist[$i]['issue_master_id'] = $cn->issue_master_id;
            $datalist[$i]['program_name'] = $cn->issueMaster->indentMaster->programs->program_name;
            $datalist[$i]['prog_date'] = $cn->issueMaster->indentMaster->programs->prog_date;
            $i++;
        }
        return sendJson('List of all issue return', $datalist, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id = request()->query('id');
        if ($id) {
            $issues = IssueMaster::with('issueChild', 'issueChild.itemInfo')->find($id);
            // return response()->json($issues);
            $result = [];
            $i = 0;
            foreach ($issues->issueChild as $index => $res) {
                $result[$i]['issue_child_id'] = $res['id'];
                $result[$i]['issue_master_id'] = $res['issue_master_id'];
                $result[$i]['item_information_id'] = $res['item_information_id'];
                $result[$i]['uom_id'] = $res['uom_id'];
                $result[$i]['uom_short_code'] = $res['uom_short_code'];
                $result[$i]['item_rate'] = $res->itemInfo->ioc_rate;
                $result[$i]['issue_qty'] = $res['issue_qty'];
                $result[$i]['display_itm_name'] = $res->itemInfo->display_itm_name;
                $i++;
            }
            $selectedIssues = [
                'issue_master_id' => $issues->id,
                'indent_master_id' => $issues->indent_master_id,
                'program_name' => $issues->indentMaster->programs->program_name,
                'program_date' => $issues->indentMaster->programs->prog_date,
                'number_of_guest' => $issues->indentMaster->programs->number_of_guest,
                'tran_source_type_id' => $issues->tran_source_type_id,
                'currency_id' => $issues->currency_id,
                'excg_rate' => $issues->excg_rate,
                'tran_type_id' => $issues->tran_type_id,
                'tran_sub_type_id' => $issues->tran_sub_type_id,
                'prod_type_id' => $issues->prod_type_id,
                'delivery_to' => $issues->delivery_to,
                'fiscal_year' => $issues->fiscal_year,
                'vat_month' => $issues->vat_month,
                'issue_number' => $issues->issue_number,
                'issue_date' => $issues->issue_date,
                'requisition_num' => $issues->requisition_num,
                'challan_number' => $issues->challan_number,
                'challan_date' => $issues->challan_date,
                'remarks' => $issues->remarks,
                'item_row' => $result,
            ];
            return sendJson('single issue rm view', $selectedIssues, 200);
        } else {
            $issues = IssueMaster::orderBy('created_at', 'DESC')->get();
            $selectedIssues = $issues->map(function ($issue) {
                return [
                    'id' => $issue->id,
                    'indent_master_id' => $issue->indent_master_id,
                    'issue_number' => $issue->issue_number,
                ];
            });
        }
        if ($issues->count() > 0) {
            return sendJson('Issue List', $selectedIssues, 200);
        }
        return sendJson('Issue List Not Found', [], 400);
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
            'issue_master_id' => 'required|numeric|exists:trns03a_issue_master,id',
            'return_to' => 'required|numeric|exists:cs_company_store_location,id',
            'return_date' => 'required|date',
            'remarks' => 'sometimes|max:255',
            'item_row.*.issue_child_id' => 'required|numeric|exists:trns03b_issue_child,id',
            'item_row.*.item_information_id' => 'required|numeric|exists:var_item_info,id',
            'item_row.*.adjt_qty' => 'required|numeric|gt:0',
        ]);
        if ($validator->fails()) {
            return sendJson('Validation Error', $validator->errors(), 200);
        }
        $comID = Auth::user()->company_id;
        $data = DB::select('CALL getTableID("trns05a_credit_note_master","' . $comID . '")');
        $issueMasterID = $data[0]->masterID;
        $chalanNumber = DB::select('CALL getTableID("5q_sv_challan_type","' . $comID . '")');
        $chalanNumber = $data[0]->masterID;
        $fiscal = fiscalYearAndMonth($request->return_date);
        $creaditNoteMaster = [
            'issue_master_id' => $request->issue_master_id,
            'credit_note_date' => $request->return_date,
            'credit_note_no' => $issueMasterID,
            'credit_note_no_bn' => $issueMasterID,
            'credit_note_for' => $request->remarks,
            'remarks' => $request->remarks,
            'vat_month' => $fiscal['vat_month'],
            'created_by' => auth()->user()->id,
        ];
        try {
            DB::beginTransaction();
            $cn = CreditNoteMaster::create($creaditNoteMaster);
            $creditChildData = [];
            foreach ($request->item_row as $index => $data) {
                $dat['credit_note_id'] = $cn->id;
                $dat['issue_child_id'] = $data['issue_child_id'];
                $dat['adjt_qty'] = $data['adjt_qty'];
                $dat['adjt_rate'] = $data['adjt_qty'] * VarItemInfo::find($data['item_information_id'])->ioc_rate;
                $dat['adjt_amount'] = $data['adjt_qty'] * VarItemInfo::find($data['item_information_id'])->ioc_rate;
                $dat['created_by'] = auth()->user()->id;
                $dat['updated_by'] = auth()->user()->id;
                array_push($creditChildData, $dat);
            }
            $cnc = CreditNoteChild::insert($creditChildData);
            $recvMaster = [
                'issue_master_id' => $request->issue_master_id,
                'fiscal_year' => $fiscal['fiscal_year'],
                'vat_month' => $fiscal['vat_month'],
                'currency_id' => $request->currency_id,
                'excg_rate' => 1,
                'chalan_number' => $chalanNumber,
                'chalan_number_bn' => $chalanNumber,
                'grn_number' => $chalanNumber,
                'grn_number_bn' => $chalanNumber,
                'chalan_date' => $request->return_date,
                'excg_rate' => $request->excg_rate,
                'prod_type_id' => 1,
                'purchase_order_date' => $request->return_date,
                'tran_source_type_id' => $request->tran_source_type_id,
                'company_id' => auth()->user()->company_id,
                'branch_id' => auth()->user()->branch_id,
                'store_id' => auth()->user()->store_id,
                'store_id' => auth()->user()->store_id,
                'receive_date' => $request->return_date,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'remarks' => $request->remarks,
                'remarks_bn' => $request->remarks,
                'grn_date' => date("Y-m-d", strtotime($request->return_date)),
            ];

            $recvMasterId = RecvMaster::create($recvMaster);
            $recvChildList = [];
            foreach ($request->item_row as $item) {
                $recvChild = [
                    'receive_master_id' => $recvMasterId->id,
                    'item_information_id' => $item['item_information_id'],
                    'uom_id' => $item['uom_id'],
                    'uom_short_code' => $item['uom_short_code'],
                    'po_quantity' => $item['issue_qty'],
                    'po_rate' => $item['item_rate'],
                    'recv_quantity' => $item['issue_qty'],
                    'itm_receive_rate' => $item['item_rate'],
                    'total_amount_local_curr' => $item['item_rate'] * $item['issue_qty'],
                ];
                array_push($recvChildList, $recvChild);
            }
            RecvChild::insert($recvChildList);
            DB::commit();
            return sendJson('Issue Returned Successfull', $cn, 200);
        } catch (\Throwable $th) {
            return sendJson($th->getMessage(), [], 400);
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
        $cns = CreditNoteMaster::with([
            'issueMaster',
            'issueMaster.indentMaster',
            'issueMaster.indentMaster.programs',
            'issueMaster.indentMaster.item_indent_child',
            'issueMaster.indentMaster.item_indent_child.itemInfo',
        ])->find($id);
        // return response()->json($cns->issueMaster->issueChild[0]);
        $items = [];
        $i = 0;
        foreach ($cns->issueMaster->issueChild as $index => $item) {
            $items[$i]['issue_child_id'] = $item->id;
            $items[$i]['item_information_id'] = $item->itemInfo->id;
            // $items[$id]['uom_id'] = $item->itemInfo->itemInfo['uom_id'];
            $items[$i]['uom_short_code'] = $item->uom_short_code;
            $items[$i]['uom_id'] = $item->itemInfo->uom_id;
            $items[$i]['display_itm_name'] = $item->itemInfo->display_itm_name;
            $items[$i]['ioc_rate'] = $item->itemInfo->ioc_rate;
            $i++;
        }
        $data = [];
        $data =
            [
                'issue_return_id' => $cns->id,
                'issue_master_id' => $cns->issue_master_id,
                'vat_month' => $cns->vat_month,
                'vat_month' => $cns->vat_month,
                'credit_note_no' => $cns->credit_note_no,
                'credit_note_for' => $cns->credit_note_for,
                'credit_note_for' => $cns->credit_note_for,
                'remarks' => $cns->remarks,
                'issue_master_id' => $cns->issueMaster->id,
                'indent_master_id' => $cns->issueMaster->indent_master_id,
                'tran_source_type_id' => $cns->issueMaster->tran_source_type_id,
                'tran_type_id' => $cns->issueMaster->tran_type_id,
                'tran_sub_type_id' => $cns->issueMaster->tran_sub_type_id,
                'currency_id' => $cns->issueMaster->currency_id,
                'fiscal_year' => $cns->issueMaster->fiscal_year,
                'issue_number' => $cns->issueMaster->issue_number,
                'issue_date' => $cns->issueMaster->issue_date,
                'requisition_num' => $cns->issueMaster->requisition_num,
                'currency_id' => $cns->issueMaster->currency_id,
                'indent_master_id' => $cns->issueMaster->indentMaster->id,
                'program_master_id' => $cns->issueMaster->indentMaster->program_master_id,
                'indent_number' => $cns->issueMaster->indentMaster->indent_number,
                'indent_date' => $cns->issueMaster->indentMaster->indent_date,
                'product_req' => $cns->issueMaster->indentMaster->product_req,
                'demand_store_id' => $cns->issueMaster->indentMaster->demand_store_id,
                'to_store_id' => $cns->issueMaster->indentMaster->to_store_id,
                'remarks' => $cns->issueMaster->indentMaster->remarks,
                'program_name' => $cns->issueMaster->indentMaster->programs->program_name,
                'program_date' => $cns->issueMaster->indentMaster->programs->program_date,
                'item_row' =>  $items,
            ];
        if ($cns) {
            return sendJson('Issue Return Found', $data, 200);
        }
        return sendJson('Issue Return Not Found', null, 400);
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
}