<?php

namespace App\Http\Controllers\Api;

use Exception;
use Throwable;
use App\Models\SvUOM;
use App\Models\Currency;
use App\Models\RecvChild;
use App\Models\RecvMaster;
use App\Models\IssueMaster;
use App\Models\VarItemInfo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ItemStockChild;
use App\Models\ItemStockMaster;
use App\Models\PurchaseReqChild;
use App\Models\PurchaseReqMaster;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\TransactionSourceType;
use App\Models\PurchaseRequisitionProdQty;

class TransferInController extends Controller
{
    public function index()
    {
        $days = request('days');
        $transferIns = RecvMaster::orderBy('created_at', 'DESC')
            ->where('tran_source_type_id', 'like', TransactionSourceType::where('tran_source_type_name', 'like', 'Receive')->first()->id)
            ->when($days, function ($query, $days) {
                $date = now()->subDays($days)->toDateString();
                return $query->whereDate('created_at', '>=', $date);
            })
            ->paginate(request('perPage', 10));
        if ($transferIns->count() > 0) {
            return sendJson('Transfer In List Loaded', $transferIns, 200);
        } else {
            return sendJson('Transfer In Not Found', $transferIns, 400);
        }
    }
    public function pendingTout()
    {
        $transferIns = IssueMaster::orderBy('created_at', 'DESC')
            ->where('tran_source_type_id', 'like', TransactionSourceType::where('tran_source_type_name', 'like', 'Issue')->first()->id)
            ->where('store_id', auth()->user()->store_id)
            ->get();
        if ($transferIns->count() > 0) {
            return sendJson('Transfer Out List Loaded', $transferIns, 200);
        } else {
            return sendJson('Transfer Out Not Found', $transferIns, 400);
        }
    }

    public function pendingToutOne($id)
    {
        $transferIns = IssueMaster::with('issueChild', 'issueChild.itemInfo')->orderBy('created_at', 'DESC')
            ->where('tran_source_type_id', 'like', TransactionSourceType::where('tran_source_type_name', 'like', 'Issue')
            ->first()->id)
            ->where('store_id', auth()->user()->store_id)
            ->first();
        // return response()->json($transferIns->issueChild);
        if ($transferIns) {
            $transferDataList = [];
            $i = 0;
            foreach ($transferIns->issueChild as $index => $data) {
                $transferDataList[$i]['issue_child_id'] = $data->id;
                $transferDataList[$i]['indent_master_id'] = $data->indent_master_id;
                $transferDataList[$i]['item_information_id'] = $data->item_information_id;
                $transferDataList[$i]['uom_id'] = $data->uom_id;
                $transferDataList[$i]['uom_short_code'] = $data->uom_short_code;
                $transferDataList[$i]['relative_factor'] = $data->relative_factor;
                $transferDataList[$i]['issue_qty'] = $data->issue_qty;
                $transferDataList[$i]['issue_qty_adjt'] = $data->issue_qty_adjt;
                $transferDataList[$i]['created_by'] = $data->created_by;
                $transferDataList[$i]['updated_by'] = $data->updated_by;
                $transferDataList[$i]['display_itm_name'] = $data->itemInfo->display_itm_name;
                $transferDataList[$i]['rate'] = $data->itemInfo->ioc_rate;
                $i++;
            }
            $data = [
                'issue_master_id'
                => $transferIns->id,
                'indent_master_id'
                => $transferIns->indent_master_id,
                'tran_source_type_id'
                => $transferIns->tran_source_type_id,
                'company_id'
                => $transferIns->company_id,
                'branch_id'
                => $transferIns->branch_id,
                'store_id'
                => $transferIns->store_id,
                'currency_id'
                => $transferIns->currency_id,
                'excg_rate'
                => $transferIns->excg_rate,
                'delivery_to'
                => $transferIns->delivery_to,
                'delivery_to_bn'
                => $transferIns->delivery_to_bn,
                'fiscal_year'
                => $transferIns->fiscal_year,
                'vat_month'
                => $transferIns->vat_month,
                'issue_number'
                => $transferIns->issue_number,
                'issue_date'
                => $transferIns->issue_date,
                'requisition_num'
                => $transferIns->requisition_num,
                'sales_invoice_date'
                => $transferIns->sales_invoice_date,
                'challan_number'
                => $transferIns->challan_number,
                'challan_date'
                => $transferIns->challan_date,
                'created_by'
                => $transferIns->created_by,
                'updated_by'
                => $transferIns->updated_by,
                'item_row'
                => $transferDataList,
            ];
            return sendJson('Transfer Out List Loaded', $data, 200);
        } else {
            return sendJson('Transfer Out Not Found', [], 400);
        }
    }
    public function tinStore(Request $request)
    {
        $fiscal = fiscalYearAndMonth($request->recieved_date);
        try {
            DB::beginTransaction();
            $branch_id = auth()->user()->branch_id;
            $created_by = auth()->user()->id;
            $current_month = date('m');
            $company_id = auth()->user()->company_id;
            $store_id = auth()->user()->store_id;
            $recvMaster = [
                'issue_master_id' => $request->issue_master_id,
                'fiscal_year' => $fiscal['fiscal_year'],
                'vat_month' => $fiscal['vat_month'],
                'currency_id' => $request->currency_id,
                'excg_rate' => 1,
                'chalan_number' => $request->challan_number,
                'chalan_number_bn' => $request->challan_number,
                'chalan_date' => $request->recieved_date,
                'excg_rate' => $request->excg_rate,
                'prod_type_id' => 1,
                'purchase_order_date' => $request->recieved_date,
                'tran_source_type_id' => $request->tran_source_type_id,
                'company_id' => auth()->user()->company_id,
                // 'reg_status' => $request->status ? 1 : 0,
                // 'is_partial' => 1,
                'branch_id' => auth()->user()->branch_id,
                'store_id' => auth()->user()->store_id,
                'store_id' => auth()->user()->store_id,
                'receive_date' => $request->recieved_date,
                // 'requisition_date' => $request->recieved_date,
                // 'submitted_by' => auth()->user()->id,
                // 'recommended_by' => auth()->user()->id,
                // 'approved_by' => auth()->user()->id,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                // 'approved_status' => 1,
                'remarks' => $request->remarks,
                'remarks_bn' => $request->remarks,
                'grn_date' => date("Y-m-d", strtotime($request->recieved_date)),
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
                    'po_rate' => $item['rate'],
                    'rec_quantity' => $item['issue_qty'],
                    'itm_receive_rate' => $item['rate'],
                    'total_amount_local_curr' => $item['rate'] * $item['issue_qty'],
                ];
                array_push($recvChildList, $recvChild);
            }
            RecvChild::insert($recvChildList);
            DB::commit();
            return response()->json($recvMasterId);
        } catch (Exception $ex) {
            DB::rollback();
            return response([
                'message' => $ex->getMessage(),
                'status' => 'failed'
            ], 400);
        }
    }
    public function tinOne($id)
    {
        $transferIn = RecvMaster::with('recvChild', 'recvChild.itemInfo')
            ->where('tran_source_type_id', 'like', TransactionSourceType::where('tran_source_type_name', 'like', 'Receive')->first()->id)
            ->find($id);
        if ($transferIn) {
            $tinData = [];
            $i = 0;
            foreach ($transferIn->recvChild as $index => $data) {
                $tinData[$i]['recv_child_id'] = $data->id;
                $tinData[$i]['receive_master_id'] = $data->receive_master_id;
                $tinData[$i]['item_information_id'] = $data->item_information_id;
                $tinData[$i]['uom_id'] = $data->uom_id;
                $tinData[$i]['uom_short_code'] = $data->uom_short_code;
                $tinData[$i]['relative_factor'] = $data->relative_factor;
                $tinData[$i]['po_quantity'] = $data->po_quantity;
                $tinData[$i]['po_rate'] = $data->po_rate;
                $tinData[$i]['rec_quantity'] = $data->rec_quantity;
                $tinData[$i]['itm_receive_rate'] = $data->itm_receive_rate;
                $tinData[$i]['created_at'] = $data->created_at;
                $tinData[$i]['display_itm_name'] = $data->itemInfo->display_itm_name;
                $tinData[$i]['rate'] = $data->ioc_rate;
                $i++;
            }
            $redata = [
                'transfer_in_id' => $transferIn->id,
                'issue_master_id' => $transferIn->issue_master_id,
                'purchase_order_date' => $transferIn->purchase_order_date,
                'tran_source_type_id' => $transferIn->tran_source_type_id,
                'company_id' => $transferIn->company_id,
                'branch_id' => $transferIn->branch_id,
                'store_id' => $transferIn->store_id,
                'currency_id' => $transferIn->currency_id,
                'excg_rate' => $transferIn->excg_rate,
                'receive_date' => $transferIn->receive_date,
                'fiscal_year' => $transferIn->fiscal_year,
                'vat_month' => $transferIn->vat_month,
                'chalan_number' => $transferIn->chalan_number,
                'chalan_date' => $transferIn->chalan_date,
                'created_by' => $transferIn->created_by,
                'updated_by' => $transferIn->updated_by,
                'item_row' => $tinData,
            ];
            return sendJson('Transfer In Loaded', $redata, 200);
        } else {
            return sendJson('Transfer In Not Found', $transferIn, 400);
        }
    }
}