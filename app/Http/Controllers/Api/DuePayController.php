<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\OrderMaster;
use Illuminate\Http\Request;
use App\Models\ProgramPayChild;
use App\Models\ProgramPayDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DuePayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $payment = ProgramPayDetail::join('trns00g_order_master', 'p_program_master_id', 'trns00g_order_master.id')
            ->orderBy('isPaidstatus','ASC')
            ->get();
        return sendJson('List of all payment', $payment, 200);
    }
    // due pay
    public function duePay()
    {
        // return ProgramPayDetail::with('orderMaster', 'orderMaster.customer')
        //     ->where('isPaidstatus', 0)
        //     ->paginate(10);
        $perPage = request('perPage', 10);
        $search = request('search', '');
        return ProgramPayDetail::leftjoin('trns00g_order_master', 'p_program_master_id', 'trns00g_order_master.id')
            ->leftjoin('cs_customer_details', 'trns00g_order_master.customer_id', '=', 'cs_customer_details.id')
            ->select('trns00g_order_master.id AS order_master_id', 'program_pay_details.*', 'trns00g_order_master.*', 'cs_customer_details.*')
            ->when($search, function ($query, $search) {
                return $query->join('trns00g_order_master', 'p_program_master_id', 'trns00g_order_master.id')
                    ->join('cs_customer_details', 'trns00g_order_master.customer_id', '=', 'cs_customer_details.id')
                    ->where('trns00g_order_master.program_name', 'like', "%{$search}%");
            })
            ->where('isPaidstatus', 0)
            ->where('trns00g_order_master.program_name', 'like', "%{$search}%")
            ->paginate($perPage);
    }
    // due pay
    public function paidPay()
    {
        $perPage = request('perPage', 10);
        $search = request('search', '');
        return ProgramPayDetail::leftjoin('trns00g_order_master', 'p_program_master_id', 'trns00g_order_master.id')
            ->leftjoin('cs_customer_details', 'trns00g_order_master.customer_id', '=', 'cs_customer_details.id')
            ->select('trns00g_order_master.id AS order_master_id', 'program_pay_details.*', 'trns00g_order_master.*', 'cs_customer_details.*')
            ->when($search, function ($query, $search) {
                return $query->join('trns00g_order_master', 'p_program_master_id', 'trns00g_order_master.id')
                    ->join('cs_customer_details', 'trns00g_order_master.customer_id', '=', 'cs_customer_details.id')
                    ->where('trns00g_order_master.program_name', 'like', "%{$search}%");
            })
            ->where('isPaidstatus', 1)
            ->where('trns00g_order_master.program_name', 'like', "%{$search}%")
            ->paginate($perPage);
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
            'p_program_master_id' => 'required|numeric|exists:trns00g_order_master,id',
            'paid_amount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        if ($validated->fails()) {
            return sendJson('validation fails', $validated->errors(), 422);
        }
        $lastPayment = ProgramPayDetail::where('p_program_master_id', $request->input('p_program_master_id'))->latest()->first();
        // return response()->json($lastPayment);
        if ($request->input('paid_amount') <= $lastPayment->due_amount) {
            $payment = ProgramPayDetail::create([
                'p_program_master_id' => $request->input('p_program_master_id'),
                'total_amount' => OrderMaster::where('id', $request->input('p_program_master_id'))->first()->total_amount,
                'paid_amount' => $request->input('paid_amount'),
                'due_amount' =>  $lastPayment->due_amount - $request->input('paid_amount'),
            ]);
            if ($payment->due_amount == 0) {
                $payment->update(['isPaidstatus' => 1]);
                return sendJson('Payment has been cleared', $payment, 200);
            }
            return sendJson('Payment has been stored', $payment, 200);
        } else {
            return sendJson('Inserted amount is bigger than due amount', null, 400);
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
        $validated = Validator::make($request->all(), [
            'p_program_master_id' => 'required|numeric|exists:trns00g_order_master,id',
            'total_amount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'paid_amount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/|lte:total_amount',
            'due_amount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/|gte:paid_amount',
        ], [
            'paid_amount.lte' => 'The paid amount must be less than or equal to the total amount.',
            'due_amount.gte' => 'The due amount must be greater than or equal to the paid amount.',
        ]);
        if ($validated->fails()) {
            return sendJson('validation fails', $validated->errors(), 422);
        }
        $payment = ProgramPayDetail::create([
            'p_program_master_id' => $request->input('p_program_master_id'),
            'total_amount' => $request->input('total_amount'),
            'paid_amount' => $request->input('paid_amount'),
            'due_amount' => $request->input('due_amount'),
        ]);
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