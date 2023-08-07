<?php

namespace App\Http\Controllers\Api;

use App\Models\Table;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\TableType;
use Illuminate\Support\Facades\Validator;

class TableController extends Controller
{
    public function index()
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', 10);
        $tables =
            Table::leftJoin('var_table_type', 'var_restaurant_table.var_table_type_id', '=', 'var_table_type.table_type_id')
            ->leftJoin('var_restaurant_room','var_restaurant_room.id','var_restaurant_table.var_restaurant_room_id')
            ->select('var_restaurant_table.*', 'var_table_type.type_name as table_type_name','var_restaurant_room.rooom_name as rooom_name')->paginate($perPage);
        return response()->json($tables);
    }

    public function store(Request $request)
    {
        $request->validate([
            'var_restaurant_room_id' => 'required|exists:var_restaurant_room,id',
            'var_table_type_id' => 'required|exists:var_table_type,table_type_id',
            'table_name' => 'required|unique:var_restaurant_table,table_name',
            'table_no' => 'required|unique:var_restaurant_table,table_no',
            'capacity' => 'required|integer',
            'status'=>'required|boolean',
        ]);
        try {

            $table = Table::create($request->all());
            return response()->json($table, 201);
            
        } catch (Exception $e) {
            return $e->getMessage();
            
        }
    }

    public function show($id)
    {
        $table = Table::leftJoin('var_table_type', 'var_restaurant_table.var_table_type_id', '=', 'var_table_type.id')
            ->select('var_restaurant_table.*', 'var_table_type.type_name as table_type_name')->find($id);

        if (!$table) {
            return response()->json(['error' => 'Table not found.'], 404);
        }

        return response()->json($table);
    }

    public function update(Request $request, $id)
    {
        $table = Table::find($id);

        if (!$table) {
            return response()->json(['error' => 'Table not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'var_restaurant_room_id' => 'required|exists:var_restaurant_room,id',
            'var_table_type_id' => 'required|exists:var_table_type,id',
            'table_name' => 'required|unique:var_restaurant_table,table_name,' . $id,
            'table_no' => 'required|unique:var_restaurant_table,table_no,' . $id,
            'capacity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $table->update($request->all());

        return response()->json($table);
    }

    public function destroy($id)
    {
        $table = Table::find($id);

        if (!$table) {
            return response()->json(['error' => 'Table not found.'], 404);
        }

        $table->delete();

        return response()->json(['message' => 'Table deleted successfully.']);
    }
    public function create()
    {
        $rooms = Room::get();
        $tabletypes = TableType::get();
        return sendJson('room and table types', ['rooms' => $rooms, 'tableTypes' => $tabletypes], 200);
    }
}