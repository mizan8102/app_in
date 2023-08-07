<?php

namespace App\Http\Controllers\Api;

use App\Models\TableType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TableTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', 10);
        $tableTypes = TableType::paginate($perPage);

        return response()->json(
            $tableTypes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'type_name' => 'required|unique:var_table_type,type_name',
            'type_name_bn' => 'required|unique:var_table_type,type_name_bn',
            'status' => 'required|boolean',
        ]);
        try {
            DB::beginTransaction();
            $tableType = TableType::create($request->all());
            DB::commit();
            return $tableType;
            
        } catch (Exception $e) {
            DB::rollBack();
            return sendJson('Table type create failed',$e->getMessage(),500);
            
        }
    }

    // Show method
    public function show($id)
    {
        $tableType = TableType::find($id);
        if (!$tableType) {
            return response()->json(['error' => 'Table type not found'], 404);
        }
        return response()->json($tableType);
    }

    // Update method
    public function update(Request $request, $id)
    {
        $tableType = TableType::find($id);
        if (!$tableType) {
            return response()->json(['error' => 'Table type not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'type_name' => 'required|unique:table_types,type_name,' . $id,
            'type_name_bn' => 'required|unique:table_types,type_name_bn,' . $id,
            'status' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $tableType->update($request->all());
        return response()->json($tableType);
    }

    // Delete method
    public function destroy($id)
    {
        $tableType = TableType::find($id);
        if (!$tableType) {
            return response()->json(['error' => 'Table type not found'], 404);
        }
        $tableType->delete();
        return response()->json(['message' => 'Table type deleted successfully']);
    }
}
