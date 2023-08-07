<?php

namespace App\Http\Controllers\Transfer;

use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\Currency;
use App\Models\IssueChild;
use App\Models\IssueMaster;
use App\Models\VarItemInfo;
use Illuminate\Http\Request;
use App\Models\TransferMaster;
use App\Models\ItemMasterModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\TransactionSourceType;
use App\Models\CsCompanyStoreLocation;
use App\Models\ToutProductReqQuantity;
use App\Http\Requests\TransferMasterRequest;
use App\Http\Controllers\Inventory\IndentController;

class TransferOutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }
    /**
     * transfer out pending data
     */
    public function pendingIndentForTransferOut()
    {
        $search = request('search', '');
        $perPage = request('perPage', 10);
        $query = ItemMasterModel::leftjoin('cs_company_store_location as store_to', 'store_to.id', 'to_store_id')
            ->leftJoin('cs_company_store_location as demand', 'demand.id', 'demand_store_id')
            ->select('trns00a_indent_master.*', 'demand.sl_name as demand_store', 'store_to.sl_name as store_name')
            ->where('close_status', 0)->where('indent_number', 'like', "%{$search}%")
            ->paginate($perPage);
        return Helper::sendJson($query);
    }

    public function transferOutIndentComplete()
    {
        $search = request('search', '');
        $perPage = request('perPage', 10);
        $data = TransferMaster::orderBy('updated_at', 'DESC')->where('challan_number', 'like', "%{$search}%")->paginate($perPage);
        return Helper::sendJson(arr: [
            "indent" => $data,
            "store" => $this->getStore()
        ]);
    }

    public function indentNumbers($id)
    {
        $pendingIndent = ItemMasterModel::with('item_indent_child', 'item_indent_child.itemInfo')->where('close_status', 0)->find($id);
        // return $pendingIndent;
        if ($pendingIndent) {
            $data = [];
            $i = 0;
            foreach ($pendingIndent->item_indent_child as $child) {
                $data[$i]['child_id'] = $child->id;
                $data[$i]['indent_master_id'] = $child->indent_master_id;
                $data[$i]['item_information_id'] = $child->item_information_id;
                $data[$i]['item_rate'] = VarItemInfo::find($child->item_information_id)->ioc_rate;
                $data[$i]['uom_id'] = $child->uom_id;
                $data[$i]['uom_short_code'] = $child->uom_short_code;
                $data[$i]['relative_factor'] = $child->relative_factor;
                $data[$i]['indent_quantity'] = $child->indent_quantity;
                $data[$i]['consum_order_qty'] = $child->consum_order_qty;
                $data[$i]['remarks'] = $child->Remarks;
                $data[$i]['required_date'] = $child->required_date;
                $data[$i]['display_itm_name'] = $child->itemInfo->display_itm_name;
                $i++;
            }
            return Helper::sendJson(arr: [
                "data" => [
                    'id' => $pendingIndent->id,
                    'program_master_id' => $pendingIndent->program_master_id,
                    'indent_number' => $pendingIndent->indent_number,
                    'chalan_number' => chalanNumber(),
                    'indent_date' => $pendingIndent->indent_date,
                    'product_req' => $pendingIndent->product_req,
                    'company_id' => $pendingIndent->company_id,
                    'branch_id' => $pendingIndent->branch_id,
                    'demand_store_id' => $pendingIndent->demand_store_id,
                    'to_store_id' => $pendingIndent->to_store_id,
                    'remarks' => $pendingIndent->remarks,
                    'submitted_by' => $pendingIndent->submitted_by,
                    'issue_status' => $pendingIndent->issue_status,
                    'close_status' => $pendingIndent->close_status,
                    'recommended_by' => $pendingIndent->recommended_by,
                    'approved_by' => $pendingIndent->approved_by,
                    'approved_status' => $pendingIndent->approved_status,
                    'created_at' => $pendingIndent->created_at->format('d-m-Y'),
                    'updated_at' => $pendingIndent->updated_at->format('d-m-Y'),
                    'created_by' => $pendingIndent->created_by,
                    'updated_by' => $pendingIndent->updated_by,
                    'item_row' => $data,
                ],
            ]);
        } else {
            return Helper::sendJson(arr: [
                "message" => 'data not found',
                "data" => [],
                "stores" => $this->getStore()
            ]);
        }
    }

    public function getStore()
    {
        return CsCompanyStoreLocation::select('id', 'sl_name')->get();
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
        $branch_id = auth()->user()->branch_id;
        $created_by = auth()->user()->id;
        $current_month = date('m');
        $company_id = auth()->user()->company_id;
        $store_id = auth()->user()->store_id;
        $challan_number = getNumber('transfer_out_challan_number');
        $fiscal = fiscalYearAndMonth($request->transfer_date);
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
                'issue_number' => issueNumber(),
                'issue_number_bn' => issueNumber(),
                'issue_date' => $request->transfer_date,
                'requisition_num' => $request->indent_number,
                'requisition_num_bn' => $request->indent_number,
                'challan_number' => $request->chalan_number,
                'challan_number_bn' => $request->chalan_number,
                'challan_date' => $request->transfer_date,
                'remarks' => $request->remarks,
                'remarks_bn' => $request->remarks,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'delivery_to' => $request->to_store_id,
                'delivery_to_bn' => $request->to_store_id,
                'currency_id' => Currency::where('currency_shortcode', 'like', 'BDT')->first()->id,
            ];
            $isseuMaster = IssueMaster::create($issueMasterData);
            $trnsMasterData = [
                'indent_master_id' => $request->indent_master_id,
                'receive_master_id' => null, // need to derive the data
                'issue_master_id' => $isseuMaster->id, // need to derive the data
                'transfer_date' => $request->transfer_date,
                'challan_number' => $request->chalan_number,
                'challan_number_bn' => $request->chalan_number,
                'challan_date' => $request->transfer_date,
                'remarks' => $request->remarks,
                'transfer_status' => 'Goods In Progress',
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'issuing_store_id' => $request->to_store_id,
            ];
            $transferMaster = TransferMaster::create($trnsMasterData);
            $issueChildData = [];
            foreach ($request->item_row as $item) {
                $issueChildDatas = [
                    'issue_master_id' => $isseuMaster->id,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'item_information_id' => $item['item_information_id'],
                    'uom_id' => $item['uom_id'],
                    'item_rate' => $item['item_rate'],
                    'uom_short_code' => $item['uom_short_code'],
                    'relative_factor' => $item['relative_factor'],
                    'issue_qty' => $item['issue_qty'],
                    'issue_qty_adjt' => $item['issue_qty'],
                ];
                $toutProdReqQtyS = [];
                $toutProdReqQty = [
                    'issue_master_id' => $isseuMaster->id,
                    'indent_child_id' => $item['child_id'],
                    'item_information_id' => $item['item_information_id'],
                    'requisition_quantity' => $item['issue_qty'],
                ];
                array_push($issueChildData, $issueChildDatas);
                array_push($toutProdReqQtyS, $toutProdReqQty);
            }
            IssueChild::insert($issueChildData);
            ToutProductReqQuantity::insert($toutProdReqQtyS);
            DB::commit();
            return Helper::sendJson(arr: [
                "message" => 'Transfer Out Successfull',
                "data" => [$isseuMaster, $issueChildData],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return Helper::sendJson(arr: [
                "message" => $th->getMessage(),
                "data" => [],
            ]);
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
        $tout = TransferMaster::find($id);
        if ($tout) {
            $issueChild = IssueChild::with('itemInfo')->where('issue_master_id', $tout->issue_master_id)->get();
            $item = [];
            $i = 0;
            foreach ($issueChild as $index => $data) {
                $item[$i]['issue_child_id'] = $data->id;
                $item[$i]['issue_master_id'] = $data->issue_master_id;
                $item[$i]['item_information_id'] = $data->item_information_id;
                $item[$i]['uom_id'] = $data->uom_id;
                $item[$i]['uom_short_code'] = $data->uom_short_code;
                $item[$i]['issue_qty'] = $data->issue_qty;
                $item[$i]['issue_qty_adjt'] = $data->issue_qty_adjt;
                $item[$i]['itm_code'] = $data->itemInfo->itm_code;
                $item[$i]['display_itm_code'] = $data->itemInfo->display_itm_code;
                $item[$i]['display_itm_name'] = $data->itemInfo->display_itm_name;
                $item[$i]['item_rate'] = $data->itemInfo->ioc_rate;
                $i++;
            }
            return Helper::sendJson(arr: [
                "message" => 'Successfully found transfer out',
                "data" => [
                    'issue_master_id' => $tout->id,
                    'indent_master_id' => $tout->indent_master_id,
                    'transfer_date' =>
                    Carbon::parse($tout->transfer_date, 'UTC')->format('d-m-Y'),
                    'challan_number' => $tout->challan_number,
                    'challan_date' => Carbon::parse($tout->challan_date, 'UTC')->format('d-m-Y'),
                    'issuing_store_id' => $tout->issuing_store_id,
                    'receiving_store_id' => $tout->receiving_store_id,
                    'issue_master_id' => $tout->issue_master_id,
                    'transfer_status' => $tout->transfer_status,
                    'created_by' => $tout->created_by,
                    'updated_by' => $tout->updated_by,
                    'item_row' => $item
                ],
            ]);
        } else {
            return Helper::sendJson(arr: [
                "message" => 'Not found transfer out',
                "data" => []
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
    }
}
