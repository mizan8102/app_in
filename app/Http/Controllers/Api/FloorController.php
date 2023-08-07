<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\Floor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsCompanyStoreLocation;
use Illuminate\Support\Facades\Validator;

class FloorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request()->input('search');
        $perPage = request()->input('perPage', 10);
        $floor = Floor::query()
            ->select('r_floor.*', 'cs_company_store_location.id as restaurant_id', 'cs_company_store_location.sl_name as restaurant_name')
            ->when($search, function ($query, $search) {
                return $query->where('floor_name', 'like', '%' . $search . '%')
                    ->orWhere('floor_name_bn', 'like', '%' . $search . '%');
            })
            ->leftJoin('cs_company_store_location', 'r_floor.r_restaurant_id', '=', 'cs_company_store_location.id')
            ->orderBy('floor_name', 'ASC')
            ->paginate($perPage);

        return sendJson('list of the program type', $floor, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $restaurant = CsCompanyStoreLocation::select('id', 'sl_name')->orderBy('sl_name', 'ASC')->get();
        return sendJson('Restaurant list', $restaurant, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'r_restaurant_id' => 'required|numeric|exists:cs_company_store_location,id',
            'floor_name' => 'required|max:100',
            'floor_name_bn' => 'required|max:100',
            'is_active' => 'required|boolean',
        ]);
        if ($validated->fails()) {
            return sendJson('validation fails', $validated->errors(), 422);
        }
        try {
            $floor = Floor::create([
                'r_restaurant_id' => $request->r_restaurant_id,
                'floor_name' => $request->program_type_name,
                'floor_name_bn' => $request->program_type_name_bn,
                'is_active' => $request->is_active,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
            return sendJson('Floor create success', $floor, 200);
        } catch (Throwable $th) {
            return sendJson('Floor create failed', $th->getMessage(), 200);
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
        $floor = Floor::select('r_floor.*', 'cs_company_store_location.id as restaurant_id', 'cs_company_store_location.sl_name as restaurant_name')->leftJoin('cs_company_store_location', 'r_floor.r_restaurant_id', '=', 'cs_company_store_location.id')->find($id);
        if ($floor) {
            return sendJson('Floor Found Success', $floor, 200);
        } else {
            return sendJson('Floor not Found', null, 200);
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
        $restaurant = CsCompanyStoreLocation::orderBy('sl_name', 'ASC')->get();
        $floor = Floor::select('r_floor.*', 'cs_company_store_location.id as restaurant_id', 'cs_company_store_location.sl_name as restaurant_name')->leftJoin('cs_company_store_location', 'r_floor.r_restaurant_id', '=', 'cs_company_store_location.id')->find($id);
        if ($floor) {
            return sendJson('Floor Found Success', $floor, 200);
        } else {
            return sendJson('Floor not Found', null, 200);
        }
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
        $restaurant = CsCompanyStoreLocation::orderBy('sl_name', 'ASC')->get();
        return sendJson('Restaurant list', $restaurant, 200);
        $floor = Floor::select('r_floor.*', 'cs_company_store_location.id as restaurant_id', 'cs_company_store_location.sl_name as restaurant_name')->leftJoin('cs_company_store_location', 'r_floor.r_restaurant_id', '=', 'cs_company_store_location.id')->find($id);
        if ($floor) {
            try {
                $validated = Validator::make($request->all(), [
                    'r_restaurant_id' => 'required|numeric|exists:cs_company_store_location,id',
                    'floor_name' => 'required|max:100',
                    'floor_name_bn' => 'required|max:100',
                    'is_active' => 'required|boolean',
                ]);
                if ($validated->fails()) {
                    return sendJson('validation fails', $validated->errors(), 422);
                }
                $floor->update([
                    'r_restaurant_id' => $request->r_restaurant_id,
                    'floor_name' => $request->program_type_name,
                    'floor_name_bn' => $request->program_type_name_bn,
                    'is_active' => $request->is_active,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ]);
                return sendJson('Floor update success', $floor, 200);
            } catch (Throwable $th) {
                return sendJson('Floor update failed', $th->getMessage(), 200);
            }
        } else {
            return sendJson('Floor  not Found', null, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $floor = Floor::find($id);
        if ($floor) {
            try {
                $floor->delete();
                return sendJson('Floor delete success', null, 200);
            } catch (Throwable $th) {
                return sendJson('Floor delete failed', $th->getMessage(), 200);
            }
        } else {
            return sendJson('Floor not Found', null, 200);
        }
    }
}
