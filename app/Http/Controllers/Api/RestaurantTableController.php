<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderMaster;
use Illuminate\Http\Request;
use App\Models\RestaurantTable;
use Illuminate\Support\Facades\DB;
use Exception;

class RestaurantTableController extends Controller
{
    public function index()
    {
        return RestaurantTable::select(
            "table_id",
            "var_restaurant_room_id",
            "var_table_type_id",
            "table_name",
            "table_no",
            "capacity",
            "status",
            "booking_status"
        )
            ->get();
    }

    public function swapTable(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $order = OrderMaster::where('order_master_id', $request->master_id)->first();
                $prev_table_id = $order->table_id;
                $order->table_id = $request->selected_table_id;
                $order->table_no = $request->selected_table_no;
                $order->save();

                $prevTable = RestaurantTable::where('table_id', $prev_table_id)->first();
                $prevTable->booking_status = 0;
                $prevTable->save();


                $currTable = RestaurantTable::where('table_id', $request->selected_table_id)->first();
                $currTable->booking_status = 1;
                $currTable->save();
            });
            return response()->json([
                "status" => "success",
                "error" => false,
                "message" => "Success! Table Swapped."
            ], 201);
        } catch (Exception $exception) {
            return response()->json(["status" => "failed", "message" => $exception->getMessage()], 404);
        }
    }
}
