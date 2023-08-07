<?php

namespace App\Http\Controllers\Api;

use Throwable;
use Exception;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserRegistrationController extends Controller
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
        return User::leftJoin('cs_company_master', 'cs_company_master.id', '=', 'users.company_id')
            ->leftJoin('cs_company_branch_unit', 'cs_company_branch_unit.id', 'users.branch_id')
            ->leftJoin('user_roles','user_roles.id','users.role_id')
           ->where('name', 'like', "%{$search}%")
            ->select('users.*', 'cs_company_master.comp_name as comp_name', 'cs_company_branch_unit.b_u_name as b_u_name','user_roles.role_name as role_name')
            ->orderBy('name', 'ASC')
            ->paginate($perPage);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = DB::table('cs_company_master')
            ->where('is_active', 1)
            ->orderBy('comp_name', 'asc')
            ->get();
        $branches = DB::table('cs_company_branch_unit')
            ->where('is_active', 1)
            ->orderBy('b_u_name', 'asc')
            ->get();

        $roles = DB::table('user_roles')
            ->orderBy('role_name', 'asc')
            ->get();

        return response()->json([
            'companies' => $companies,
            'branches' => $branches,
            'roles' => $roles,
        ]);
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
            'name' => 'required|max:255',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone' => 'required|max:255',
            'company_id' => 'required|numeric|exists:cs_company_master,id',
            'branch_id' => 'required|numeric|exists:cs_company_branch_unit,id',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|max:255',
            'address' => 'required|max:255',
            'role_id' => 'required|exists:user_roles,id',
        ]);
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'address' => $request->address,
                'role_id' => $request->role_id,
                'created_at'=>now(),
            ]);
            DB::commit();
            return $user;
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json($th->getMessage());
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
        $user = User::findOrFail($id);
        return sendJson('User Details', $user, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return sendJson('User Details', $user, 200);
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
        $request->validate([
            'name' => 'required|max:255',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone' => 'required|max:255',
            'company_id' => 'required|numeric|exists:cs_company_master,id',
            'branch_id' => 'required|numeric|exists:cs_company_branch_unit,id',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|max:255',
            'address' => 'required|max:255',
            'role_id' => 'required|numeric|exists:cs_users_roles,id',
        ]);
        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->phone = $request->phone;
            $user->company_id = $request->company_id;
            $user->branch_id = $request->branch_id;
            $user->email = $request->email;
            $user->address = $request->address;
            // $user->role_id = $request->role_id;
            if ($request->has('password')) {
                $user->password = bcrypt($request->password);
            }
            $user->save();
            DB::commit();
            return sendJson('User updated successfully', $user, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendJson('User update failed', $th->getMessage(), 400);
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
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->delete();
            DB::commit();
            return sendJson('User Deleted Successfully', null, 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return sendJson('User Delete Failed', $th->getMessage(), 400);
        }
    }
}
