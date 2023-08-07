<?php

namespace App\Http\Controllers\Api;

use App\Models\HouseKeeping\CsSupplierContactInfo;
use Throwable;
use Illuminate\Http\Request;
use App\Models\SupplierDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SupplierProfileController extends Controller
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
        $supplier = SupplierDetail::query()
        ->select('cs_supplier_details.*','cs_supplier_contact_info.phone')
        ->leftJoin('cs_supplier_contact_info','cs_supplier_details.id','cs_supplier_contact_info.supplier_id')
            ->when($search, function ($query, $search) {
                return $query->where('supplier_name', 'like', '%' . $search . '%')
                    ->orWhere('supplier_name_bn', 'like', '%' . $search . '%')
                    ->orWhere('supplier_bin_number', 'like', '%' . $search . '%')
                    ->orWhere('supplier_bin_number_bn', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('supplier_name_bn', 'like', '%' . $search . '%');
            })
            ->orderBy('supplier_name', 'ASC')
            ->paginate($perPage);

        return sendJson('list of the supplier details', $supplier, 200);
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
            'supplier_name' => 'required|max:100',
            'supplier_name_bn' => 'required|max:100',
            'supplier_bin_number' => 'nullable',
            'supplier_bin_number_bn' => 'nullable|max:100',
            'email_address' => 'nullable|max:100',
            'phone_number' => 'required|max:20',
            'address' => 'required|max:200',
            
         
        ]);
        try {
            DB::beginTransaction();
            $supplier = SupplierDetail::create([
                'supplier_name' => $request->supplier_name,
                'supplier_name_bn' => $request->supplier_name_bn,
                'supplier_bin_number' => $request->supplier_bin_number,
                'supplier_bin_number_bn' => $request->supplier_bin_number_bn,
                'email_address' => $request->email,
                // 'phone_number' => $request->phone_number,
                'address' => $request->address,
               
            ]);
            CsSupplierContactInfo::create([
                "supplier_id" => $supplier->id,
                "phone" =>$request->phone_number,
            ]);
            DB::commit();
            return sendJson('Supplier Create Success', $supplier, 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return sendJson('Supplier Create Success', $th->getMessage(), 400);
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
        $supplier = SupplierDetail::find($id);
        if ($supplier) {
            return sendJson('Supplier Found', $supplier, 200);
        } else {
            return sendJson('Supplier Not Found', $supplier, 400);
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
        $supplier = SupplierDetail::find($id);
        if ($supplier) {
            return sendJson('Supplier Found', $supplier, 200);
        } else {
            return sendJson('Supplier Not Found', $supplier, 400);
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
        $supplier = SupplierDetail::find($id);
        if ($supplier) {
             $request->validate([
            'supplier_name' => 'required|max:100',
            'supplier_name_bn' => 'required|max:100',
            'supplier_bin_number' => 'required|max:100',
            'supplier_bin_number_bn' => 'required|max:100',
            'email' => 'required|max:100',
            'phone_number' => 'required|max:20',
            'address' => 'required|max:200',
            'fax_number' => 'required|max:20',
        ]);
            try {
                DB::beginTransaction();
                $supplier->update([
                    'supplier_name' => $request->supplier_name,
                    'supplier_name_bn' => $request->supplier_name_bn,
                    'supplier_bin_number' => $request->supplier_bin_number,
                    'supplier_bin_number_bn' => $request->supplier_bin_number_bn,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'fax_number' => $request->fax_number,
                ]);
                DB::commit();
                return sendJson('Supplier Update Success', $supplier, 200);
            } catch (Throwable $th) {
                DB::rollback();
                return sendJson('Supplier Update Success', $th->getMessage(), 400);
            }
        } else {
            return sendJson('Supplier Not Found', $supplier, 400);
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
        $supplier = SupplierDetail::find($id);
        if ($supplier) {
            try {
                return sendJson('Supplier Success', $supplier, 200);
            } catch (Throwable $th) {
                return sendJson('Supplier Success', $th->getMessage(), 400);
            }
        } else {
            return sendJson('Supplier Not Found', $supplier, 400);
        }
    }
}