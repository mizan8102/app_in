<?php

namespace App\Http\Controllers\EventManagement;

use App\Http\Controllers\Controller;
use App\Models\dbview\DueCottageList;
use App\Models\dbview\DueEventList;
use App\Models\dbview\RestaurantPaymentList;
use App\Models\IssueChild;
use App\Models\IssueMaster;
use App\Models\OrderChild;
use App\Models\OrderMaster;
use App\Models\PaymentMaster;
use Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class EvenDueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $type       = request('type',"");
        $perPage    = request('perPage',"");
        $search     = request('search','');
        $showAll    = request('showAll',0);
        $filter_data= request('filter_data',0);
        if($perPage){
            if($type =="cottageDue"){
                $query = DueCottageList::query();
            }else if($type == "restaurantDue"){
                $query = RestaurantPaymentList::query();
            }else{
                $query = DueEventList::query();
            }
           
       
            if ($filter_data == 30) {
                $query->where('order_date', '>', Carbon::now()->subMonth());
            } elseif ($filter_data == 15) {
                $query->where('order_date', '>', Carbon::now()->subDays(15));
            } elseif ($filter_data == 7) {
                $query->where('order_date', '>', Carbon::now()->subWeek());
            }
            if ($showAll == 0) {
                $query->where('due_amount', '>', 0);
            } 

            if($type == "restaurantDue"){
                $query->where('id', 'like', "%{$search}%")->orderByDesc('id');
                return $query->paginate($perPage);
            }
            $query->where('customer_phone', 'like', "%{$search}%")->orderByDesc('id');
            return $query->paginate($perPage);
            
           
        }else{
            if($type == "cottageDue"){
                return DueCottageList::where('due_amount', '>', 0)->get();
            }else if($type == "restaurantDue"){
                return RestaurantPaymentList::where('due_amount', '>', 0)->get();
            }else{
               return DB::select("CALL due_event_list()"); 
            } 
        }
        
        
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
        try{ 
            // 
            DB::beginTransaction();
            $issue_id=$request->issue_master;
            if($issue_id == null){
                DB::beginTransaction();
                  $issueData = $this->issue_master_store($request);
                  $issue = IssueMaster::create($issueData);
                  $data= OrderChild::where('order_master_id',$request->order_id)->get();
                  foreach($data as $order_child){
                   
                      IssueChild::create([
                          "issue_master_id"           => $issue->id,
                          "item_info_id"              => $order_child['item_info_id'],
                          "uom_id"                    => $order_child['uom_id'],
                          "uom_short_code"            => $order_child['uom_short_code'],
                          "relative_factor"           => $order_child['relative_factor'],
                          "item_rate"                 => $order_child['item_rate'],
                          "issue_qty"                 => $order_child['order_qty'],
                          "vat_percent"               => $order_child['vat_percent'],
                          "vat_amount"                => $order_child['vat_amount'],
                          "discount"                  => $order_child['discount'],
                          "total_amount_local_curr"   => $order_child['total_amount_local_curr'] != null ? $order_child['total_amount_local_curr'] :0,
                          "created_by"                => Auth::id()
                      ]);
                }
                $issue_id = $issue->id;
                OrderMaster::where('id',$request->order_id)->update(['issue_master_id' => $issue->id]);
            }
            $pay_master_id=PaymentMaster::create([
                "issue_master_id"   => $issue_id,
                "order_id"          => $request->order_id,
                "payment_date"      => date('Y-m-d',strtotime($request->paid_date)),
                "paymode_id"        => $request->paymode,
                "paid_amount"       => $request->paid_amt,
                "pay_ref"           => $request->refference,
                "remarks"           => $request->remarks,
                "created_by"        => Auth::user()->id,
            ]);
            if(intval($request->after_due) ==0){
                 OrderMaster::where('id',$request->order_id)->update(['status' => 1]);      
            }
                 
            DB::commit();
        return $pay_master_id;
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    function issue_master_store(Request $req):array{
        $issue_number=issue_number();
            $issue=[
                "tran_source_type_id"                           => config('globals.trans_source_type_Issue'),
                "tran_type_id"                                  => config('globals.tran_type_id_sells'),
                "tran_sub_type_id"                              => config('globals.tran_sub_type_id'),
                "prod_type_id"                                  => 1,
                "company_id"                                    => Auth::user()->company_id,
                "branch_id"                                     => Auth::user()->branch_id,
                "store_id"                                      => Auth::user()->store_id,
                "currency_id"                                   => config('globals.default_excg_rate'),
                "excg_rate"                                     => config('globals.default_excg_rate'),
                "customer_id"                                   => $req->customer_id,
                "issue_number"                                  => $issue_number,
                "issue_number_bn"                               => $issue_number,
                "issue_date"                                    => date('Y-m-d',strtotime($req->paid_date)),
                "total_issue_amount_before_discount"            => $req->total_amount,
                "total_issue_amt_local_curr_before_discount"    => $req->total_amount,
                "total_discount"                                => $req->order_discount,
                "total_issue_amt_local_curr"                    => $req->total_amount,
                "total_vat_amnt"                                => $req->total_vat_amt,
                "challan_date"                                  =>date('Y-m-d H:m:s'),
                "created_by"                                    => Auth::user()->id,
            ];
            return $issue;
    }

    /**
     * @param int $order_master
     * @param int $issue_master_id
     * @return void
     * order child data add in the issue child table
     */
    function order_child_store($order_master_id, $issue_mater_id){
        try{
            $data= OrderChild::where('order_master_id',$order_master_id)->get();
            foreach($data as $order_child){
                IssueChild::create([
                    "issue_master_id"           => $issue_mater_id,
                    "item_info_id"              => $order_child['item_info_id'],
                    "uom_id"                    => $order_child['uom_id'],
                    "uom_short_code"            => $order_child->uom_short_code,
                    "relative_factor"           => $order_child->relative_factor,
                    "item_rate"                 => $order_child->item_rate,
                    "issue_qty"                 => $order_child->order_qty,
                    "vat_percent"               => $order_child->vat_percent,
                    "vat_amount"                => $order_child->vat_amount,
                    "discount"                  => $order_child->discount,
                    "total_amount_local_curr"   => $order_child->total_amount_local_curr,
                    "created_by"                => Auth::id()
                ]);
            }
        }catch(Exception $ex){
            throw new Exception($ex->getMessage());
        }
    }

    public function payement_histories_event(int $id){
             return PaymentMaster::leftJoin('5x4_paymode_type','5x4_paymode_type.id','trns50a_payment_master.paymode_id')
                            ->select('trns50a_payment_master.*','5x4_paymode_type.paymode_name')
                            ->where('order_id',$id)
                            ->orderByDesc('id')
                            ->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return OrderMaster::with(['paymentmaster'=>function($query){
            $query->select('trns50a_payment_master.id as pay_id','trns50a_payment_master.*','5x4_paymode_type.*')->leftJoin('5x4_paymode_type','5x4_paymode_type.id','trns50a_payment_master.paymode_id')->orderByDesc('5x4_paymode_type.id');
        }])
        ->select('trns00g_order_master.*','cs_company_store_location.sl_name',
        '5z2_program_type.program_type_name','cs_customer_details.customer_name','session_name','var_program_sessions.start_time',
        'var_program_sessions.end_time','5x3_order_type.type_name')
        ->leftJoin('cs_company_store_location','cs_company_store_location.id','trns00g_order_master.floor_id')
        ->leftJoin('5x3_order_type','5x3_order_type.id','trns00g_order_master.order_type_id')
        ->leftJoin('5z2_program_type','5z2_program_type.id','trns00g_order_master.program_type_id')
        ->leftJoin('cs_customer_details','cs_customer_details.id','trns00g_order_master.customer_id')
        ->leftJoin('var_program_sessions','var_program_sessions.id','program_session_id')
        ->find($id);
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
