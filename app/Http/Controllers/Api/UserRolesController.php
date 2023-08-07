<?php

namespace App\Http\Controllers\Api;

use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search', '');
        $perPage = request('perPage', 10);
        $store = UserRole::where('role_name', 'like', "%{$search}%")
            ->orWhere('role_name_bn', 'like', "%{$search}%")
            ->paginate($perPage);
        return sendJson('store location list', $store, 200);
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
            'role_name' => 'required|unique:cs_users_roles,role_name,except,id',
            'role_name_bn' => 'required|unique:cs_users_roles,role_name_bn,except,id',
        ]);
        if ($validated->fails()) {
            return sendJson('validation fails', $validated->errors(), 422);
        }
        try {
            DB::beginTransaction();
            $role = UserRole::create([
                'role_name' => $request->role_name,
                'role_name_bn' => $request->role_name_bn,
            ]);
            DB::commit();
            return sendJson('store location', $role, 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return sendJson('user role failed', $th->getMessage(), 500);
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
        $role = UserRole::find($id);
        return sendJson('user role', $role, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = UserRole::find($id);
        return sendJson('user role', $role, 200);
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
        $role = UserRole::find($id);
        $validated = Validator::make($request->all(), [
            'role_name' => 'required|unique:cs_users_roles,role_name,except,id',
            'role_name_bn' => 'required|unique:cs_users_roles,role_name_bn,except,id',
        ]);
        if ($validated->fails()) {
            return sendJson('validation fails', $validated->errors(), 422);
        }
        try {
            DB::beginTransaction();
            $role->update([
                'role_name' => $request->role_name,
                'role_name_bn' => $request->role_name_bn,
            ]);
            DB::commit();
            return sendJson('store location', $role, 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return sendJson('user role failed', $th->getMessage(), 500);
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
            $role = UserRole::find($id)->delete();
            return sendJson(' user role delete', $role, 200);
        } catch (\Throwable $th) {
            return sendJson('user role delete failed', $th->getMessage(), 500);
        }
    }
}
