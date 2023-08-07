<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\ProgramType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProgramTypesController extends Controller
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
        $programType = ProgramType::query()
            ->when($search, function ($query, $search) {
                return $query->where('program_type_name', 'like', '%' . $search . '%')
                    ->orWhere('program_type_name_bn', 'like', '%' . $search . '%');
            })
            ->orderBy('program_type_name', 'ASC')
            ->paginate($perPage);
        return sendJson('list of the program type', $programType, 200);
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
            'program_type_name' => 'required|max:100|unique:r_program_type,program_type_name',
            'program_type_name_bn' => 'required|max:100|unique:r_program_type,program_type_name',
            'is_active' => 'required|boolean',
        ]);
        if ($validated->fails()) {
            return sendJson('validation fails', $validated->errors(), 422);
        }
        try {
            $programType = ProgramType::create([
                'restaurant_master_id' => 1,
                'program_type_name' => $request->program_type_name,
                'program_type_name_bn' => $request->program_type_name_bn,
                'is_active' => $request->is_active,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
            return sendJson('program type create success', $programType, 200);
        } catch (Throwable $th) {
            return sendJson('program type create failed', $th->getMessage(), 200);
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
        $programType = ProgramType::find($id);
        if ($programType) {
            return sendJson('program type found', $programType, 200);
        } else {
            return sendJson('program type not found', null, 400);
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
        $programType = ProgramType::find($id);
        if ($programType) {
            return sendJson('program type found', $programType, 200);
        } else {
            return sendJson('program type not found', null, 400);
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
        $programType = ProgramType::find($id);
        if ($programType) {
            $validated = Validator::make($request->all(), [
                'program_type_name' => 'required|max:100|unique:r_program_type,program_type_name,except,id',
                'program_type_name_bn' => 'required|max:100|unique:r_program_type,program_type_name,except,id',
                'is_active' => 'required|boolean',
            ]);
            if ($validated->fails()) {
                return sendJson('validation fails', $validated->errors(), 422);
            }
            try {
                $programType->update([
                    'restaurant_master_id' => 1,
                    'program_type_name' => $request->program_type_name,
                    'program_type_name_bn' => $request->program_type_name_bn,
                    'is_active' => $request->is_active,
                    'updated_by' => auth()->user()->id,
                ]);
                return sendJson('program type update success', $programType, 200);
            } catch (Throwable $th) {
                return sendJson('program type update failed', $th->getMessage(), 400);
            }
        } else {
            return sendJson('program type not found', null, 400);
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
        $programType = ProgramType::find($id);
        if ($programType) {
            try {
                $programType->delete();
                return sendJson('Program delete success', null, 200);
            } catch (Throwable $th) {
                return sendJson('Program delete failed', $th->getMessage(), 400);
            }
        } else {
            return sendJson('program type not found', null, 400);
        }
    }
}
