<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SvUOM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HouseKeeping\UomSet;
use Illuminate\Support\Facades\Validator;

class HouseKeepingUOMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search', '');
        $paginate = request('perPage', 10);
        return SvUOM::leftJoin('5l_sv_uom_set', '5l_sv_uom_set.id', '5m_sv_uom.uom_set_id')->orderBy('id', 'DESC')->select('5m_sv_uom.*', '5l_sv_uom_set.id As uom_set_id', '5l_sv_uom_set.uom_set As uom_set')->paginate($paginate);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return UomSet::select('id', 'uom_set', 'uom_set_desc', 'local_uom_set_desc', 'is_active')
            ->orderBy('id', 'DESC')->get();
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
            'uom_set_id' => 'required|integer|gte:0',
            'uom_short_code' => 'required',
            'uom_desc' => 'required',
            'local_desc' => 'required',
            'relative_factor' => 'required',
            'uom_short_code' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            DB::beginTransaction();
            $uom = SvUOM::create($request->all());
            DB::commit();
            return $uom;
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendJson('UOM create failed', $th->getMessage(), 400);
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
        $uom = SvUOM::find($id);
        return sendJson('UOM found successfully', $uom, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $uom = SvUOM::find($id);
        return sendJson('UOM found successfully', $uom, 200);
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
        $uom = SvUOM::find($id);
        $validator = Validator::make($request->all(), [
            'uom_set_id' => 'required|integer|gte:0',
            'uom_short_code' => 'required',
            'uom_desc' => 'required',
            'local_desc' => 'required',
            'relative_factor' => 'required',
            'uom_short_code' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            DB::beginTransaction();
            $uom->update($request->all());
            DB::commit();
            return $uom;
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendJson('UOM update failed', $th->getMessage(), 400);
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
        try {
            $uom = SvUOM::find($id)->delete();
            return sendJson('UOM deleted success', null, 200);
        } catch (\Throwable $th) {
            return sendJson('UOM deleted failed', $th->getMessage(), 200);
        }
    }
}
