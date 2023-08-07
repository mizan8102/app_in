<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RFloor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class RFloorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RFloor::select("id", "r_restaurant_id", "floor_name", "floor_name_bn", "is_active")->get();
    }


    public function getRFloorsWithParam(Request $request)
    {
        if ($request->limit <= 0) {
            $limit = 10;
        } else {
            $limit = $request->limit;
        }

        $search_input = $request->search;
        if ($search_input) {
            return RFloor::select("id", "r_restaurant_id", "floor_name", "floor_name_bn", "is_active")
                ->where('floor_name', 'like', '%' . $search_input . '%') 
                ->paginate($limit);
        } else {
            return RFloor::select("id", "r_restaurant_id", "floor_name", "floor_name_bn", "is_active")->paginate($limit);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            "floor_name" => "required",
            "floor_name_bn" => "required",
            "is_active" => "required", 
        ]);

        if ($validator->fails()) {
            return $this->validationErrors($validator->errors())->setStatusCode(406);
        }

        try {
            $floor = RFloor::create([
                "floor_name" => $request->floor_name,
                "floor_name_bn" => $request->floor_name_bn, 
                "is_active" => $request->is_active,
                "created_by" => Auth::user()->id
            ]);

            return response()->json([
                "status" => "success",
                "error" => false,
                "inserted_id" => $floor->id,
                "message" => "Success! Floor created."
            ], 201);
        } catch (Exception $exception) {
            return response()->json(["status" => "failed", "message" => $exception->getMessage()], 404);
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
        $floor = RFloor::find($id);

        if ($floor) {
            $validator = Validator::make($request->all(), [
                "floor_name" => "required",
                "floor_name_bn" => "required",
                "is_active" => "required", 
            ]);

            if ($validator->fails()) {
                return $this->validationErrors($validator->errors())->setStatusCode(406);
            }
            $floor['floor_name'] = $request->floor_name;
            $floor['floor_name_bn'] = $request->floor_name_bn;
            $floor['is_active'] = $request->is_active;  
            $floor['updated_by'] = Auth::user()->id;

            $floor->save();

            return response()->json([
                "status" => "success",
                "error" => false,
                "updated_id" => $floor->id,
                "message" => "Success! Floor updated."
            ], 201);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed! no floor found."], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $floor = RFloor::where('id', $id)->delete();
        if ($floor) {
            return response()->json(["status" => "success", "error" => false, "message" => "Success! floor deleted."], 200);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed! no floor found."], 404);
    }

    /**
     * Change active status of an item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($id)
    {
        $floor = RFloor::find($id);
        if ($floor) {

            if ($floor['is_active'] == 1) {
                $floor['is_active'] = 0;
            } else {
                $floor['is_active'] = 1;
            }

            $floor->save();

            return response()->json([
                "status" => "success",
                "error" => false,
                "updated_id" => $floor->id,
                "message" => "Success! Status updated."
            ], 201);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed! no floor found."], 404);
    }
}
