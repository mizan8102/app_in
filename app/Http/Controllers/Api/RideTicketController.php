<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\IssueChild;
use App\Models\IssueMaster;
use App\Models\ProductType;
use App\Models\SubGroup;
use App\Models\TransactionSourceType;
use App\Models\TransactionSubType;
use App\Models\TransactionType;
use App\Models\VarItemInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Milon\Barcode\DNS1D;


class RideTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request()->input('search');
        $productType = ProductType::where('prod_type_name', 'like', 'Service')->first();
        $subGroup = SubGroup::where('itm_sub_grp_des', 'like', 'Ride')->first();
        if ($productType) {
            $data = VarItemInfo::with('item_detail')
                ->where('prod_type_id', $productType->id)
                ->where('itm_sub_grp_id', $subGroup->id)
                ->orderBy('display_itm_name', 'ASC')->get();
            $items = [];
            $i = 0;
            foreach ($data as $key => $value) {
                $items[$i]['id'] = $value['id'];
                $items[$i]['display_itm_code'] = $value['display_itm_code'];
                $items[$i]['display_itm_name'] = $value['display_itm_name'];
                $items[$i]['uom_id'] = $value['uom_id'];
                $items[$i]['active'] = false;
                $items[$i]['issue_qty'] = 1;
                $items[$i]['lineTotal'] = $value['item_detail']['price'];
                $items[$i]['uom_short_code'] = $value['uom_short_code'];
                $items[$i]['price'] = $value['item_detail']['price'];
                $items[$i]['todays_sell'] = todaysSells($value['id']) ? todaysSells($value['id']) : 0;
                $items[$i]['image'] = url('ride/'.$value['item_detail']['item_image']);
                $i++;
            }
        } else {
            $items = [];
        }
        return sendJson('Rides and Service List', $items, 200);
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
            'item_row.*.id' => 'required|numeric|gte:3|exists:var_item_info,var_item_info',
            'item_row.*.uom_id' => 'required',
            'item_row.*.price' => 'required',
            'item_row.*.uom_short_code' => 'required',
            'item_row.*.issue_qty' => 'required',
        ]);
        $tranSourceType = TransactionSourceType::where('tran_source_type_name', 'like', 'Issue')->first();
        $tranType = TransactionType::where('tran_type_name', 'like', 'Sells')->first();
        $tranSubType = TransactionSubType::where('tran_sub_type_name', 'like', 'Local Sells')->first();
        $comID = Auth::user()->company_id;
        $data = DB::select('CALL getTableID("trns03a_issue_master","' . $comID . '")');
        $issueMasterID = $data[0]->masterID;
        $branch_id = auth()->user()->branch_id;
        $created_by = auth()->user()->id;
        $current_month = date('m');
        $company_id = auth()->user()->company_id;
        $store_id = auth()->user()->store_id;
        $fiscal = fiscalYearAndMonth(now());
        $total_issue_amount = 0;
        foreach ($request->item_row as $item) {
            $total_issue_amount += $item['issue_qty'] * $item['price'];
        }
        // return response()->json($total_issue_amount);
        try {
            DB::beginTransaction();
            $issueMaster = IssueMaster::create([
                'tran_source_type_id' => $tranSourceType->id,
                'tran_type_id' => $tranType->id,
                'tran_sub_type_id' => $tranSubType->id,
                'company_id' => $comID,
                'branch_id' => $branch_id,
                'prod_type_id' => 5,
                'store_id' => $store_id,
                'fiscal_year' => $fiscal['fiscal_year'],
                'vat_month' => $fiscal['vat_month'],
                'issue_number' =>  $issueMasterID,
                'issue_number_bn' =>  $issueMasterID,
                'issue_date' => now(),
                'sales_invoice_date' => now(),
                'total_issue_amount' => $total_issue_amount,
                'total_issue_amt_local_curr' => $total_issue_amount,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'currency_id' => Currency::where('currency_shortcode', 'like', 'BDT')->first()->id,
            ]);
            // return response()->json($issueMaster);
            foreach ($request->item_row as $item) {
                IssueChild::create([
                    'issue_master_id' => $issueMaster->id,
                    'item_information_id' => $item['id'],
                    'uom_id' => $item['uom_id'],
                    'item_rate' => $item['price'],
                    'issue_qty' => $item['issue_qty'],
                    'issue_rate' => $item['price'],
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ]);
            }
            DB::commit();
            return sendJson('Ticket Sold', $issueMaster, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendJson('Ticket Sold Failed', $th->getMessage(), 400);
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
    public function reciept()
    {
        $issueMaster = IssueMaster::with('issueChild', 'issueChild.itemInfo.item_detail')->find(request()->input('id'));
        $data = [];
        foreach ($issueMaster->issueChild as $item) {
            for ($k = 0; $k < $item->issue_qty; $k++) {
                $ticketNumber = Helper::codeGenerate("ticket");
                $singleData = [
                    'issue_child_id' => $item->id,
                    'issue_qty' => 1,
                    'item_information_id' => $item->item_information_id,
                    'ticket_number' => $ticketNumber,
                    // 'bar_code' => DNS1D::getBarcodeHTML('3422333322', 'UPCA'),
                    'issue_number' => $issueMaster->issue_number,
                    'ticket_date' => $issueMaster->created_at,
                    'display_itm_name' => $item->itemInfo->display_itm_name,
                    'price' => $item->itemInfo->item_detail->price,
                ];
                array_push($data, $singleData);
            }
        }
        return sendJson('Reciept Data Generated', $data, 200);
    }

}