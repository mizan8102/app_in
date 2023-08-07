<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\RecvMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\PaymentToSupplierChild;
use App\Models\PaymentToSupplierMaster;
use Illuminate\Support\Facades\Validator;

class PaymentToSuppliarController extends Controller
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
        return PaymentToSupplierMaster::leftJoin('cs_supplier_details','cs_supplier_details.id','trns52a_pay_to_sup_master.supplier_id')
        ->select('trns52a_pay_to_sup_master.*','cs_supplier_details.supplier_name')
        ->where('trns52a_pay_to_sup_master.id', 'like', "%{$search}%")
        ->paginate($perPage);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $search = request('search', '');
        $perPage = request('perPage', 10);
        $supplier_id= request('supplier_id','');
        return RecvMaster::leftJoin('cs_supplier_details','cs_supplier_details.id','trns02a_recv_master.supplier_id')
        ->leftJoin('trns00e_purchase_order_master','trns00e_purchase_order_master.id','trns02a_recv_master.purchase_order_master_id')
        ->leftJoin('trns52b_pay_to_sup_child','trns52b_pay_to_sup_child.recv_master_id',"=",'trns02a_recv_master.id')
        // ->distinct('trns52b_pay_to_sup_child.recv_master_id')
        ->select('trns02a_recv_master.*','cs_supplier_details.supplier_name',
        'trns00e_purchase_order_master.purchase_order_number', 'trns00e_purchase_order_master.purchase_order_date')
        ->where('trns02a_recv_master.supplier_id',$supplier_id)
        ->whereNotIn('trns02a_recv_master.id', function ($query) {
                $query->select('recv_master_id')
                    ->from('trns52b_pay_to_sup_child');
            })
        ->where('trns02a_recv_master.id', 'like', "%{$search}%")
        ->paginate($perPage);
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
            'paid_amount' => 'required|numeric|gte:1',
            'supplier_id' => 'required',
            'payment_date' => 'required|date|before_or_equal:now',
            'item_row.*.id' => 'required|numeric|exists:trns02a_recv_master,id|unique:trns52b_pay_to_sup_child,recv_master_id',
            'item_row.*.total_receive_amount' => 'required|numeric|gte:1',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validated->errors()
            ], 422);
        }
        try {
            DB::beginTransaction();
            $paymentToSupplierMaster = PaymentToSupplierMaster::create([
                'payment_date' => date('Y-m-d',strtotime($request->payment_date)),
                'supplier_id' => $request->supplier_id,
                'total_paid_amount' => $request->paid_amount,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
            $supplierChild = [];

            foreach ($request->item_row as $item) {
                $data['master_id'] = $paymentToSupplierMaster->id;
                $data['recv_master_id'] = $item['id'];
                $data['recv_amount'] = $item['total_receive_amount'];
                $data['paid_amount'] = $item['total_receive_amount'];
                $data['created_by'] = auth()->user()->id;
                $data['updated_by'] = auth()->user()->id;
                array_push($supplierChild, $data);
            }
            PaymentToSupplierChild::insert($supplierChild);
            DB::commit();
            return sendJson('Payment to Supplier Done', $paymentToSupplierMaster, 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return sendJson('Payment to Supplier Failed', $th->getMessage(), 400);
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
        $payment = PaymentToSupplierMaster::with(['item_row'=>function($fn){
            $fn->with('receivemaster');
        },'supplier'])->find($id);
        return sendJson('Payments to Supplier', $payment, 200);
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
    public function merge()
    {
        $recvMasterIds = request()->input('recvMasterIds');
        $recvMaster = RecvMaster::select('id', 'grn_number', 'receive_date', 'total_receive_amount', 'purchase_order_date')->whereIn('id', $recvMasterIds)->get();
        $data = [
            'paid_amount' => $recvMaster->sum('total_receive_amount'),
            'item_row' => $recvMaster,
        ];
        return response()->json($data);
    }
}