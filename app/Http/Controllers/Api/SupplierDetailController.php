<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupplierDetail; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Auth;
use Exception;

class SupplierDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        return SupplierDetail::select(
            // "vat_reg_id",
            "sup_id", 
            "supplier_id", 
            "supplier_name", 
            "supplier_name_bn", 
            "supplier_bin_number", 
            "supplier_bin_number_bn", 
            "is_active", 
            "phone_number",  
            "email_address",
            "fax_number",
            "address", 
        ) 
        ->get(); 
    }

    public function getSuppliersWithParam(Request $request)
    {  
            $search = request('search', '');
            $limit = request('limit', 10);
            return DB::table('cs_supplier_details')
            ->select(
                'cs_supplier_details.supplier_id',
                'cs_supplier_details.supplier_name',
                'cs_supplier_details.supplier_name_bn',
                'cs_supplier_details.supplier_bin_number',
                'cs_supplier_details.supplier_bin_number_bn', 
                'cs_supplier_details.is_active',
                'cs_supplier_details.sup_id',
                'cs_supplier_details.phone_number',
                'cs_supplier_details.email_address',
                'cs_supplier_details.fax_number',
                'cs_supplier_details.address', 
                '5a_sv_vat_registration_type.vat_reg_name',  
            )  
            ->leftJoin('5a_sv_vat_registration_type','5a_sv_vat_registration_type.vat_reg_id','=','cs_supplier_details.vat_reg_id')  
            ->where('cs_supplier_details.supplier_name', 'like', '%' . $search . '%')
            ->paginate($limit);
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
        $validator = Validator::make($request->all(), [
            // "vat_reg_id" => "required",
            "supplier_name" => "required",
            "supplier_name_bn" => "required",
            // "supplier_bin_number" => "required",
            // "supplier_bin_number_bn" => "required", 
            "phone_number" => "required", 
            "is_active" => "required", 
        ]);
        $comID = Auth::user()->company_id;
        $datah = DB::select('CALL getTableID("cs_supplier_details","'.$comID.'")');
        $reqMasterID = $datah[0]->masterID;

        if ($validator->fails()) {
            return $this->validationErrors($validator->errors())->setStatusCode(406);
        }

        try {
            $supplier = SupplierDetail::create([
                "sup_id" => $reqMasterID,
                "supplier_name" => $request->supplier_name, 
                "supplier_name_bn" => $request->supplier_name_bn,
                "supplier_bin_number" => $request->supplier_bin_number,
                "supplier_bin_number_bn" => $request->supplier_bin_number_bn,
                "phone_number" => $request->phone_number,
                "address" => $request->address,
                "email_address" => $request->email_address,
                "fax_number" => $request->fax_number,
                "is_active" => $request->is_active, 
                "created_by" => Auth::user()->id
            ]);

            return response()->json([
                "status" => "success",
                "error" => false,
                "inserted_id" => $supplier->supplier_id,
                "message" => "Success! Supplier created."
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
        $supplier = SupplierDetail::find($id);

        if ($supplier) {
            $validator = Validator::make($request->all(), [
                // "vat_reg_id" =>             "required",
                "supplier_name" =>          "required",
                "supplier_name_bn" =>       "required",
                // "supplier_bin_number" =>    "required",
                // "supplier_bin_number_bn" => "required",
                "phone_number" =>           "required",
                "is_active" =>              "required",
            ]);

            if ($validator->fails()) {
                return $this->validationErrors($validator->errors())->setStatusCode(406);
            }
            // $supplier['vat_reg_id'] =                   $request->vat_reg_id;
            $supplier['supplier_name'] =                $request->supplier_name;
            $supplier['supplier_name_bn'] =             $request->supplier_name_bn;
            $supplier['supplier_bin_number'] =          $request->supplier_bin_number;
            $supplier['supplier_bin_number_bn'] =          $request->supplier_bin_number;
            $supplier['phone_number'] =                 $request->phone_number;
            $supplier['address'] =                      $request->address;
            $supplier['email_address'] =                $request->email_address;
            $supplier['fax_number'] =                   $request->fax_number;
            $supplier['is_active'] =                    $request->is_active;   
            $supplier['updated_by'] =                   Auth::user()->id;

            $supplier->save();

            return response()->json([
                "status" => "success",
                "error" => false,
                "updated_id" => $supplier->supplier_id,
                "message" => "Success! supplier updated."
            ], 201);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed! no supplier found."], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = SupplierDetail::where('supplier_id', $id)->delete();
        if ($supplier) {
            return response()->json(["status" => "success", "error" => false, "message" => "Success! supplier deleted."], 200);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed! no supplier found."], 404);
    }


    public function changeStatus($id)
    {
        $supplier = SupplierDetail::find($id);
        if ($supplier) {

            if ($supplier['is_active'] == 1) {
                $supplier['is_active'] = 0;
            } else {
                $supplier['is_active'] = 1;
            }

            $supplier->save();

            return response()->json([
                "status" => "success",
                "error" => false,
                "updated_id" => $supplier->id,
                "message" => "Success! Status updated."
            ], 201);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed! no supplier found."], 404);
    }
    /**
     * init supplier id 
     */


     public function getSupplierID(){

        // $latest = DB::table('pur_accounts')->latest()->select('voucher_no')->first();
        // if($latest){
        //     $invoice=$latest->voucher_no;
        // }else{
        //     $invoice=null;
        // }
        // $purchaseID = $this->invoiceNumber($latest,$invoice,"pur-");
        // return response()->json(['purchaseID' => $purchaseID]);
    }

    public function invoiceNumber($latest,$val,$pre)
    {
        // if (! $latest) {
        //     return $pre.'1';
        // }
        // $string = preg_replace("/[^0-9\.]/", '', $val);
        // return  $pre.$string+1;
    }

}
