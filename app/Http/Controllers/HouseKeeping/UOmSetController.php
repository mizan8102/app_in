<?php

namespace App\Http\Controllers\HouseKeeping;

use App\Http\Controllers\Controller;
use App\Models\HouseKeeping\UomSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UOmSetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search=\request('search','');
        $paginate=\request('paginate',5);
        $data= UomSet::select('id','uom_set','uom_set_desc','local_uom_set_desc','is_active')->where('uom_set','like', '%' . $search . '%')
            ->orderBy('id','DESC')->paginate($paginate);
        return response()->json([
            'all_uom_set' => $data
        ]);
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
        $validated = Validator::make($request->all(), [
            'uom_set' => 'required',
            'uom_set_desc' => 'required',
            'local_uom_set_desc' => 'required|string|max:255',

        ]);
        if ($validated->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validated->errors()
            ], 422);
        }
        return UomSet::create([
            'uom_set' => $request->uom_set,
            'uom_set_desc' => $request->uom_set_desc,
            'local_uom_set_desc' => $request->local_uom_set_desc,
            'is_active' => $request->is_active,
            'created_by' => Auth::user()->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return UomSet::find($id);
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
        //
    }
}
