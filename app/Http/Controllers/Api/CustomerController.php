<?php

namespace App\Http\Controllers\Api;

use Auth;
use Throwable;
use Illuminate\Http\Request;
use App\Models\CustomerDetails;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\CustomerContactInfo;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
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
        $customer = CustomerDetails::query()
        ->leftJoin('cs_customer_contact_info','cs_customer_contact_info.customer_id','cs_customer_details.id')
            ->when($search, function ($query, $search) {
                return $query->where('customer_name', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%')
                    ->orWhere('email_address', 'like', '%' . $search . '%');
            })
            ->orderBy('customer_name', 'ASC')
            ->select('cs_customer_details.*','cs_customer_contact_info.contact_person As contact_person'
                ,'cs_customer_contact_info.phone As contact_person_phone_number')
            ->paginate($perPage);
        return sendJson('list of the customer', $customer, 200);
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
        $request->validate([
            'customer_name' => 'required|max:100',
            'phone_number' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $customer = CustomerDetails::create([
                'customer_name'     => $request->customer_name,
                'customer_name_bn'  => $request->customer_name_bn,
                'address'           => $request->address,
                'is_active'         => 1,
                'created_by'        => Auth::id(),
                'company_id'        => Auth::user()->company_id
            ]);
            $customerInfo = CustomerContactInfo::create([
                'customer_id'       => $customer->id,
                'contact_person'    => $request->contact_person,
                'phone'             => $request->phone_number,
                'email_address'     => $request->email,
                'created_by'        => Auth::id()
            ]);
            DB::commit();
            $data=[
                "id" => $customer->id,
                "customer_name" => $customer->customer_name,
                "phone" => $customerInfo->phone,
                "contact_person" => $customerInfo->contact_person
            ];
            return sendJson('Customer Created Successfully', $data, 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return sendJson('Customer Create failed', $th->getMessage(), 200);
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
        $customer = CustomerDetails::find($id);
        if ($customer) {
            return sendJson('Customer Found', $customer, 200);
        } else {
            return sendJson('Customer Not Found', null, 400);
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
        $customer = CustomerDetails::find($id);
        if ($customer) {
            return sendJson('Customer Found', $customer, 200);
        } else {
            return sendJson('Customer Not Found', null, 400);
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
        $customer = CustomerDetails::find($id);
        if ($customer) {
            return sendJson('Customer Found', $customer, 200);
        } else {
            return sendJson('Customer Not Found', null, 400);
        }
        $validated = Validator::make($request->all(), [
            'customer_name' => 'required|max:100',
            'customer_name_bn' => 'required|max:100',
            'phone_number' => 'required',
            'email_address' => 'required|email',
            'address' => 'required|max:200',
            'is_active' => 'required|max:1|min:0|boolean',
            // 'contact_person_name' => 'required|max:50',
            // 'contact_person_phone' => 'required',
        ]);
        if ($validated->fails()) {
            return sendJson('validation fails', $validated->errors(), 422);
        }
        try {
            DB::beginTransaction();
            $customer->update([
                'customer_name' => $request->customer_name,
                'customer_name_bn' => $request->customer_name_bn,
                'phone_number' => $request->phone_number,
                'email_address' => $request->email_address,
                'address' => $request->address,
                'is_active' => $request->is_active,
            ]);
            DB::commit();
            return sendJson('Customer Created Successfully', $customer, 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return sendJson('Customer Create failed', $th->getMessage(), 200);
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
        $customer = CustomerDetails::find($id);
        if ($customer) {
            try {
                $customer->delete();
                return sendJson('Customer Deleted Successfully', $customer, 200);
            } catch (\Throwable $th) {
                return sendJson('Customer Delete Failed', $th->getMessage(), 400);
            }
        } else {
            return sendJson('Customer Not Found', null, 400);
        }
    }
}
