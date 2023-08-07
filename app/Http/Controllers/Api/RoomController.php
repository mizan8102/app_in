<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CsCompanyStoreLocation;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $query = Room::query()->leftJoin('cs_company_store_location', 'cs_company_store_location.id', 'var_restaurant_room.store_id');
        if ($request->has('search')) {
            $search = $request->input('search');
            $query
                ->orWhere('rooom_name', 'LIKE', "%$search%")
                ->orWhere('rooom_name_bn', 'LIKE', "%$search%");
        }
        $rooms = $query->select('var_restaurant_room.*', 'cs_company_store_location.sl_name as restaurant_name')->paginate($perPage);
        return response()->json(['data' => $rooms]);
    }

    public function show($id)
    {
        $room = Room::leftJoin('cs_company_store_location', 'var_restaurant_room.store_id', '=', 'cs_company_store_location.id')->select('var_restaurant_room.*', 'cs_company_store_location.sl_name as restaurant_name')->findOrFail($id);
        return response()->json(['data' => $room]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:cs_company_store_location,id',
            'rooom_name' => 'required|unique:var_restaurant_room,rooom_name',
            'rooom_name_bn' => 'required|unique:var_restaurant_room,rooom_name',
        ]);

        try {
            DB::beginTransaction();
            $room = Room::create([
                'rooom_name'=>$request->rooom_name,
                'rooom_name_bn'=>$request->rooom_name_bn,
                'store_id'=>$request->store_id,
            ]);
            DB::commit();
            return response()->json(['data' => $room], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'store_id' => 'required|exists:cs_company_store_location,id',
            'rooom_name' => 'required|unique:var_restaurant_room,rooom_name,except,id',
            'rooom_name_bn' => 'required|unique:var_restaurant_room,rooom_name,except,id',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            $room->update($validator);
            DB::commit();
            return response()->json(['data' => $room]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $room = Room::findOrFail($id);
            $room->delete();
            DB::commit();
            return response()->json([], 204);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function create()
    {
        $stores = CsCompanyStoreLocation::get();
        return sendJson('List of restaurants', $stores, 200);
    }
}
