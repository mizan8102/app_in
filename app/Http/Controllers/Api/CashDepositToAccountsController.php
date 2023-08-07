<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CashDeposit;
use App\Models\CsCompanyStoreLocation;
use App\Models\Paymode_type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CashDepositToAccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', "");
        $sells_point=request()->input('sales_point', null);
        $transaction_date=request()->input('transaction_date', null) ;
        if($transaction_date){
               $date=date('Y-m-d',strtotime($transaction_date));
        }else{
            $date="";
        }
        $query=CashDeposit::query();
        if ($sells_point) {
            $query->where('trns51a_cash_deposit.store_id', $sells_point);
        }

        if ($search) {
            $query->where('trns51a_cash_deposit.id', 'like', "%{$search}%");
        }
        if ($date) {
            $query->where('trns51a_cash_deposit.deposit_date', $date);

        }

    return $query->with('depositor')->select('trns51a_cash_deposit.*',
    'cs_company_store_location.sl_name',
    )->leftJoin('cs_company_store_location','cs_company_store_location.id','trns51a_cash_deposit.store_id')
        ->orderBy('trns51a_cash_deposit.id', 'DESC')->paginate($perPage);
}



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $salesPointOne = request()->input('sales_point_id');
        $dstore = CsCompanyStoreLocation::where('is_sales_point', 1)
            ->where('is_active', 1)
            ->orderBy('created_at', 'DESC')
            ->get(['id', 'sl_name']);
            $salesPoint=[
                "store"=>$dstore,
                'Pyment_mode'=>Paymode_type::all()

            ];
        if ($salesPointOne) {
            $salesPoint = CsCompanyStoreLocation::with('user')
                ->where('is_active', 1)
                ->orderBy('created_at', 'DESC')
                ->find($salesPointOne);
            $items = [];
            $i = 0;
            foreach ($salesPoint->user as $index => $data) {
                $items[$i]['id'] = $data->id;
                $items[$i]['name'] = $data->first_name." ".$data->last_name;
                $i++;
            }
            
            $salesPoint = [
                'id' => $salesPoint->id,
                'sl_name' => $salesPoint->sl_name,
                'item_row' => $items,
            ];
        } 
        return sendJson('Store location loaded', $salesPoint , 200);
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
            'deposit_date' => 'required|date|before_or_equal:now',
            'sale_point' => 'required|numeric|gte:1|exists:cs_company_store_location,id',
            'payment_mode' => 'required',
            'user' => 'required|numeric|gte:1|exists:users,id',
            'deposit_amount' => 'required|numeric|gte:1|regex:/^(?=.*[1-9])\d{0,15}(?:\.\d{1,2})?$/'
        ]);
        if ($validator->fails()) {
            return sendJson('Validation Error', $validator->messages(), 400);
        }
        try {
            $deposit = CashDeposit::create([
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'user_id' => $request->user,
                'paymode_id' => $request->payment_mode,
                'store_id' =>$request->sale_point,
                'deposit_date' =>date('Y-m-d',strtotime($request->deposit_date)),
                'transaction_date' =>date('Y-m-d',strtotime($request->transaction_date)),
                'remarks' =>$request->remarks,
                'deposit_amount' => $request->deposit_amount,
            ]);
            return sendJson('Money Deposit to Accounts Success', $deposit, 200);
        } catch (\Throwable $th) {
            return sendJson('Money Deposit to Accounts failed', $th->getMessage(), 400);
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
        $item = CashDeposit::find($id);
        $data["id"] = $item->id;
        $data["depositor_id"] = $item->id;
        $data["sell_point"] = $item->depositor['name'];
        $data["created_by"] = $item->id;
        $data["created_by"] = $item->createdBy['name'];
        $data["deposit_date"] = Carbon::parse($item->deposit_date)->format('Y-m-d');
        $data["payment_mode"] = $item->id;
        $data["deposit_amount"] = $item->deposit_amount;
        if ($item) {
            return sendJson('Money Deposit Success', $data, 200);
        } else {
            return sendJson('Money Deposit failed', null, 200);
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