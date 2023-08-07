<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CsUsersStores;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CsCompanyStoreLocation;
use Illuminate\Support\Facades\Validator;

class UserStoreMapppingController extends Controller
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
        $mappings = CsUsersStores::leftjoin('users','users.id','cs_users_stores.user_id')
        ->leftJoin('cs_company_store_location','cs_company_store_location.id','cs_users_stores.store_id')
        ->select('cs_users_stores.*',DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS user_name"),'cs_company_store_location.sl_name as sl_name')
            ->paginate($perPage);
        return sendJson('List of all the user store mapping', $mappings, 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mappings = CsUsersStores::get();
        // name = first_name . last_name
        $users = User::where('banned', 0)
    ->whereNotIn('id', $mappings->pluck('user_id'))
    ->selectRaw("CONCAT(first_name, ' ', last_name) AS name, id")
    ->get();
        $stores = CsCompanyStoreLocation::where('is_active', 1)
            ->whereNotIn('id', $mappings->pluck('store_id'))
            ->select('sl_name','id')
            ->get();
            $branches = DB::table('cs_company_branch_unit')
            ->where('is_active', 1)
            ->orderBy('b_u_name', 'asc')
            ->select('b_u_name','id')
            ->get();
        return response()->json(['users' => $users, 'stores' => $stores,'branches'=>$branches]);
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
            'user_id' => 'required|exists:users,id',
            'store_id' => 'required|exists:cs_company_store_location,id',
            'is_default'=>'required|boolean',
        ]);
        $store = CsCompanyStoreLocation::find($request->store_id);
        try {
            DB::beginTransaction();
            $mapping = CsUsersStores::create([
                'user_id' => $request->user_id,
                'store_id' => $request->store_id,
                'store_name' => $store->sl_name,
                'store_name_bn' => $store->sl_name_bn,
                'is_default'=>$request->is_default,
            ]);
            DB::commit();
            return sendJson('User mapped to supplier', $mapping, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendJson('User mapped to supplier failed', $th->getMessage(), 500);
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
        $mapping = CsUsersStores::find($id);
        return sendJson('SIngle mapping loaded', $mapping, 200);
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
        $mapping = CsUsersStores::find($id);
        $validated = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'store_id' => 'required|exists:cs_company_store_location,id',
            'store_name' => 'required|exists:cs_company_store_location,sl_name',
            'store_name_bn' => 'required|exists:cs_company_store_location,sl_name_bn',
        ]);
        if ($validated->fails()) {
            return sendJson('validation fails', $validated->errors(), 422);
        }
        try {
            DB::beginTransaction();
            $mapping->update([
                'user_id' => $request->user_id,
                'store_id' => $request->store_id,
                'store_name' => $request->store_name,
                'store_name_bn' => $request->store_name_bn,
            ]);
            DB::commit();
            return sendJson('User mapped to supplier', $mapping, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendJson('User mapped to supplier failed', $th->getMessage(), 500);
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
        //
    }
}