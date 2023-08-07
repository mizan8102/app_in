<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderMaster;
use App\Models\IssueMaster;
use App\Models\IssueChild;
use App\Models\OrderChild;
use App\Models\OrderStatus;
use App\Models\RestaurantTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "order_type" => "required",
        ]);
        if ($validator->fails()) {
            return $this->validationErrors($validator->errors())->setStatusCode(406);
        }
        $o_m_id = null;
        try {
            DB::transaction(function () use ($request, &$o_m_id) {
                if ($request->has('supp_master_id')) {
                    $order_master = OrderMaster::where('order_master_id', $request->supp_master_id)->first();
                    $s_total = $request->cart_data['subtotal'];
                    $g_total = $request->cart_data['cart_total'];
                    $v_amnt = $request->cart_data['vat_amnt'];
                    $order_master->order_status = 1;
                    $order_master->total_amount += $s_total;
                    $order_master->grand_total_amount += $g_total;
                    $order_master->vat_amount += $v_amnt;
                    $order_master->save();
                } else {
                    $order_id_last = 1001;
                    $order_no_row = OrderMaster::select('order_master_id', 'order_id')->orderByDesc('order_master_id')->limit(1)->first();
                    if ($order_no_row) {
                        $order_id_last = $order_no_row->order_id + 1;
                    }
                    $order_master = OrderMaster::create([
                        "order_type" => $request->order_type,
                        "order_id" => $order_id_last,
                        "table_id" => $request->table_id,
                        "table_no" => $request->table_no,
                        "customer_phone" => $request->customer_phone,
                        "total_amount" => $request->cart_data['subtotal'],
                        "total_discount" => 0,
                        "vat_amount" => $request->cart_data['vat_amnt'],
                        "grand_total_amount" => $request->cart_data['cart_total'],
                        "order_date" => date("Y-m-d H:i:s"),
                        "waiter_user_id" => $request->waiter_id,
                        "store_id" => Auth::user()->store_id,
                        "order_status" => 1,
                    ]);
                }
                if ($request->table_id) {
                    RestaurantTable::where('table_id', $request->table_id)->update(['booking_status' => 1]);
                }
                $o_m_id = $order_master->order_master_id;
                foreach ($request->cart_data['items'] as $key => $item) {
                    OrderChild::create([
                        "order_master_id" => $order_master->order_master_id,
                        "item_information_id" => $item['item_id'],
                        "quantity" => $item['qty'],
                        "rate" => $item['price'],
                        "is_supplimentary" => $request->is_supp,
                        "note" => array_key_exists('note', $item) ? $item['note'] : "",
                        "total_amount" => $item['price'] * $item['qty'],
                        "itm_sub_grp_id" => $item['item_sub_grp'],
                        "itm_grp_id" => $item['item_grp'],
                        "process_status" => 1,
                    ]);
                }

                // $comID = Auth::user()->company_id;
                // $data = DB::select('CALL getTableID("trns03a_issue_master","' . $comID . '")');
                // $issueMasterID = $data[0]->masterID;
                $issue_master = IssueMaster::create([
                    "indent_master_id" => 0,
                    "company_id" => Auth::user()->company_id,
                    "branch_id" => Auth::user()->branch_id,
                    "store_id" => Auth::user()->store_id,
                    "currency_id" => 0,
                    "reg_status" => 0,
                    // "issue_number" => $issueMasterID,
                    // "issue_number_bn" => $issueMasterID,
                    "issue_number" => 0,
                    "issue_number_bn" => 0,
                    "issue_date" => date("Y-m-d H:i:s"),
                    "employee_id" => $request->waiter_id,
                    "department_id" => 0,
                    "requisition_num" => 0,
                    "requisition_num_bn" => 0,
                    "sales_invoice_date" => date("Y-m-d H:i:s"),
                    "delivery_date" => date("Y-m-d H:i:s"),
                    "challan_type" => 'VAt Challan Number',
                    "vehicle_num" => 0,
                    "vehicle_num_bn" => 0,
                    "total_discount" => 1,
                    "total_issue_amount" => $request->cart_data['cart_total'],
                    "total_issue_amt_local_curr" => $request->cart_data['cart_total'],
                    "challan_date" => date("Y-m-d H:i:s"),
                    "received_by" => 1, 
                ]);

                foreach ($request->cart_data['items'] as $key => $item) {
                    IssueChild::create([
                        "issue_master_id" => $issue_master->issue_master_id,
                        "item_information_id" => $item['item_id'],
                        "uom_id" => 0,
                        "uom_short_code" => 0,
                        "relative_factor" => 0,
                        "issue_qty" => $item['qty'],
                        "issue_qty_adjt" => $item['qty'],
                        "issue_rate" => $item['price'],
                        "mrp_value" => $item['price'],
                        "discount" => 1,
                        "item_value_tran_curr" => $item['price'],
                        "item_value_local_curr" => $item['price'],
                        "vat_rate_type_id" => 0,
                        "total_amount_local_curr" => $item['price'] * $item['qty'],
                        "trn_unit" => 0,
                        "inventory_method" => 0,
                        "itm_trade_rate" => 0,
                    ]);
                }
            });

            return response()->json([
                "status" => "success",
                "order_master_id" => $o_m_id,
                "error" => false,
                "message" => "Success! Order Placed."
            ], 201);
        } catch (Exception $exception) {
            return response()->json(["status" => "failed", "message" => $exception->getMessage()], 404);
        }
    }

    public function show($id)
    {
        return OrderMaster::select(
            'order_master_id',
            'order_id',
            'order_type',
            'table_id',
            'table_no',
            'total_amount',
            'total_discount',
            'vat_amount',
            'grand_total_amount',
            'order_status',
            'customer_phone',
            'order_date',
        )
            ->with(['order_childs_main' => function ($query) {
                $query->select(
                    'order_child_id',
                    'order_master_id',
                    'item_information_id',
                    'quantity',
                    'item_estimated_time',
                    'process_status',
                    'is_supplimentary',
                    'note',
                    'rate',
                )
                    ->with(['item_info' =>  function ($query) {
                        $query->select('description', 'description_bn', 'item_information_id');
                    }, 'p_status' => function ($query) {
                        $query->select('id', 'name');
                    }]);
            }, 'o_status' => function ($query) {
                $query->select('id', 'name');
            }])
            ->with(['order_childs_supp' => function ($query) {
                $query->select(
                    'order_child_id',
                    'order_master_id',
                    'item_information_id',
                    'quantity',
                    'item_estimated_time',
                    'process_status',
                    'is_supplimentary',
                    'note',
                    'rate',
                )
                    ->with(['item_info' =>  function ($query) {
                        $query->select('description', 'description_bn', 'item_information_id');
                    }, 'p_status' => function ($query) {
                        $query->select('id', 'name');
                    }]);
            }, 'o_status' => function ($query) {
                $query->select('id', 'name');
            }])
            ->where('order_master_id', $id)
            ->first();
    }

    public function getKitchenOrders()
    {
        return OrderMaster::select(
            'order_master_id',
            'order_id',
            'order_type',
            'table_id',
            'table_no',
            'grand_total_amount',
            'order_date',
            'total_est_time',
            'order_status',
        )
            ->with(['order_childs_main' => function ($query) {
                $query->select(
                    'order_child_id',
                    'order_master_id',
                    'item_information_id',
                    'quantity',
                    'item_estimated_time',
                    'process_status',
                    'is_supplimentary',
                    'note',
                )
                    ->with(['item_info' =>  function ($query) {
                        $query->select('description', 'description_bn', 'item_information_id');
                    }, 'p_status' => function ($query) {
                        $query->select('id', 'name');
                    }]);
            }, 'o_status' => function ($query) {
                $query->select('id', 'name');
            }])
            ->with(['order_childs_supp' => function ($query) {
                $query->select(
                    'order_child_id',
                    'order_master_id',
                    'item_information_id',
                    'quantity',
                    'item_estimated_time',
                    'process_status',
                    'is_supplimentary',
                    'note',
                )
                    ->with(['item_info' =>  function ($query) {
                        $query->select('description', 'description_bn', 'item_information_id');
                    }, 'p_status' => function ($query) {
                        $query->select('id', 'name');
                    }]);
            }, 'o_status' => function ($query) {
                $query->select('id', 'name');
            }])
            ->where('order_status', '<', 7)
            ->orderByDesc('order_master_id')
            ->get();
    }

    public function getWaiterOrders()
    {
        return OrderMaster::select(
            'order_master_id',
            'order_id',
            'order_type',
            'table_id',
            'table_no',
            'grand_total_amount',
            'order_date',
            'total_est_time',
            'order_status',
        )
            ->with(['order_childs_main' => function ($query) {
                $query->select(
                    'order_child_id',
                    'order_master_id',
                    'item_information_id',
                    'quantity',
                    'item_estimated_time',
                    'process_status',
                    'is_supplimentary',
                    'note',
                )
                    ->with(['item_info' =>  function ($query) {
                        $query->select('description', 'description_bn', 'item_information_id');
                    }, 'p_status' => function ($query) {
                        $query->select('id', 'name');
                    }]);
            }, 'o_status' => function ($query) {
                $query->select('id', 'name');
            }])
            ->with(['order_childs_supp' => function ($query) {
                $query->select(
                    'order_child_id',
                    'order_master_id',
                    'item_information_id',
                    'quantity',
                    'item_estimated_time',
                    'process_status',
                    'is_supplimentary',
                    'note',
                )
                    ->with(['item_info' =>  function ($query) {
                        $query->select('description', 'description_bn', 'item_information_id');
                    }, 'p_status' => function ($query) {
                        $query->select('id', 'name');
                    }]);
            }, 'o_status' => function ($query) {
                $query->select('id', 'name');
            }])
            ->where('order_status', '<', 7)
            ->orderByDesc('order_master_id')
            ->get();
    }


    public function getCompletedOrders()
    {
        return OrderMaster::select(
            'order_master_id',
            'order_id',
            'order_type',
            'table_id',
            'table_no',
            'grand_total_amount',
            'order_date',
            'total_est_time',
            'total_amount',
            'total_discount',
            'vat_amount',
            'order_status',
        )
            ->with(['order_childs_main' => function ($query) {
                $query->select(
                    'order_child_id',
                    'order_master_id',
                    'item_information_id',
                    'quantity',
                    'item_estimated_time',
                    'process_status',
                    'is_supplimentary',
                    'total_amount',
                    'rate',
                    'note',
                )
                    ->with(['item_info' =>  function ($query) {
                        $query->select('description', 'description_bn', 'item_information_id');
                    }, 'p_status' => function ($query) {
                        $query->select('id', 'name');
                    }]);
            }, 'o_status' => function ($query) {
                $query->select('id', 'name');
            }])
            ->with(['order_childs_supp' => function ($query) {
                $query->select(
                    'order_child_id',
                    'order_master_id',
                    'item_information_id',
                    'quantity',
                    'item_estimated_time',
                    'process_status',
                    'is_supplimentary',
                    'note',
                )
                    ->with(['item_info' =>  function ($query) {
                        $query->select('description', 'description_bn', 'item_information_id');
                    }, 'p_status' => function ($query) {
                        $query->select('id', 'name');
                    }]);
            }, 'o_status' => function ($query) {
                $query->select('id', 'name');
            }])
            ->where('order_status', 7)
            ->orderByDesc('order_master_id')
            ->get();
    }

    public function orderUpdate(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $master_id = null;
                foreach ($request->childs as $key => $item) {
                    $chRow = OrderChild::where('order_child_id', $item['ch_id'])->first();
                    if ($item['time'] && $chRow->process_status < 3) {
                        $chRow->process_status = 3;
                    }
                    $chRow->item_estimated_time = $item['time'];
                    $master_id = $chRow->order_master_id;
                    $chRow->save();
                }

                $topProcessStatus = OrderChild::select('process_status')
                    ->orderByDesc('process_status')
                    ->where('order_master_id',  $master_id)
                    ->limit(1)
                    ->first();

                OrderMaster::where('order_master_id', $request->master_id)
                    ->update([
                        'total_est_time' => $request->total_time,
                        'order_status' => $topProcessStatus->process_status,
                    ]);
            });
            return response()->json([
                "status" => "success",
                "error" => false,
                "message" => "Success! Order Updated."
            ], 201);
        } catch (Exception $exception) {
            return response()->json(["status" => "failed", "message" => $exception->getMessage()], 404);
        }
    }


    public function getOrderStatuses()
    {
        return OrderStatus::select('id', 'name')->get();
    }


    public function updateOrderStatus(Request $request)
    {
        try {
            $master = OrderMaster::where('order_master_id', $request->master_id)->first();
            if ($request->has('status')) {
                $master->order_status =  $request->status;
            } else {
                if ($master->order_status < 2) {
                    $master->order_status = 2;
                    OrderChild::where('order_master_id', $request->master_id)->update(['process_status' => 2]);
                }
            }
            $master->save();
            return response()->json([
                "status" => "success",
                "error" => false,
                "message" => "Success! Order Status Updated."
            ], 201);
        } catch (Exception $exception) {
            return response()->json(["status" => "failed", "message" => $exception->getMessage()], 404);
        }
    }


    public function closeOrderOperations(Request $request)
    {  
        try {
            DB::transaction(function () use ($request) {
                $master = OrderMaster::where('order_master_id', $request->master_id)->first();
                $master->order_status =  7;
                if ($request->trns_no) {
                    $master->transaction_num =  $request->trns_no;
                }
                if ($request->remarks) {
                    $master->remarks =  $request->remarks;
                }
                if ($request->input_vat_amnt) {
                    $master->vat_amount =  $request->input_vat_amnt;
                }
                if ($request->input_dis_amnt) {
                    $master->total_discount =  $request->input_dis_amnt;
                }
                if ($request->grand_total_after_calculation) {
                    $master->grand_total_amount =  $request->grand_total_after_calculation;
                }
                if ($request->payment_type) {
                    $master->pay_type =  $request->payment_type;
                }
                $master->save();

                if ($master->table_id) {
                    $table = RestaurantTable::where('table_id', $master->table_id)->first();
                    $table->booking_status = 0;
                    $table->save();
                }
            });

            return response()->json([
                "status" => "success",
                "error" => false,
                "message" => "Success! Order Status Updated."
            ], 201);
        } catch (Exception $exception) {
            return response()->json(["status" => "failed", "message" => $exception->getMessage()], 404);
        }
    }

    public function updateProcessStatus(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $master_id = null;

                $child = OrderChild::where('order_child_id', $request->child_id)->first();
                $child->process_status = $request->status;
                $master_id = $child->order_master_id;
                $child->save();

                $topProcessStatus = OrderChild::select('process_status')
                    ->where('order_master_id',  $master_id)
                    ->orderByDesc('process_status')
                    ->limit(1)
                    ->first();

                $master = OrderMaster::where('order_master_id', $child->order_master_id)->first();
                $master->order_status =  $topProcessStatus->process_status;
                $master->save();
            });
            return response()->json([
                "status" => "success",
                "error" => false,
                "message" => "Success! Process Status Updated."
            ], 201);
        } catch (Exception $exception) {
            return response()->json(["status" => "failed", "message" => $exception->getMessage()], 404);
        }
    }
}
