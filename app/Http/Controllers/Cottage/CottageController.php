<?php

namespace App\Http\Controllers\Cottage;

use App\Http\Controllers\Controller;
use App\Models\CsCustomerDetails;
use App\Models\OrderChild;
use App\Models\Orderchiledate;
use App\Models\OrderMaster;
use App\Models\OrderStatus;
use App\Models\VarItemInfo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CottageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     /**
      * cottage color
      */
    public $cottage_1="#00A70A";
    public $cottage_2= "#000000";
    public $cottage_4= "#720C0C";
    public function index()
    {
        $first_date= request('start_date','');
        $last_date  = request('end_date', '');
        $order = Orderchiledate::query();
        $order_prev=$order->leftJoin('trns00h_order_child','trns00h_order_child.id','trns00h1_order_child_date.order_child_id')
        ->select('trns00g_order_master.order_status','5x3_order_status.type_name','trns00h_order_child.id as id','var_item_info.display_itm_name','cs_customer_details.customer_name',
        'trns00h1_order_child_date.from_date','trns00h1_order_child_date.to_date','var_item_info.itm_code')
        ->leftJoin('trns00g_order_master','trns00g_order_master.id','trns00h_order_child.order_master_id')
        ->leftJoin('var_item_info','var_item_info.id','trns00h_order_child.item_info_id')
        ->leftJoin('cs_customer_details','cs_customer_details.id','trns00g_order_master.customer_id')
        ->leftJoin('5x3_order_status','5x3_order_status.id','trns00g_order_master.order_status');
        if ($first_date) {
            $query = $order_prev->orWhereBetween('from_date', [$first_date, $last_date])
                ->orWhereBetween('to_date', [$first_date, $last_date]);
        } else {
            $query = $order_prev;
        } 
        $after_order = $query->get();
        $result=[];
        $i=0;
        foreach($after_order as $or){
            $result[$i]['id']       = $or->id;
            $result[$i]['title']    = $or->customer_name. "-- ☉ ".$or->type_name;
            $result[$i]['start']    = date('Y-m-d',strtotime($or->from_date));
            $result[$i]['end']      = date('Y-m-d',strtotime($or->to_date));
           
            if(intval($or->order_status)==1){
                $result[$i]['textColor']= "white";
                $result[$i]['title']    = $or->customer_name. "--✅ ".$or->type_name;
            }else if(intval(($or->order_status)) == 2){
                $result[$i]['textColor']= "yellow";
                $result[$i]['title']    = $or->customer_name. "-- 🚀 ".$or->type_name;
            }else if(intval(($or->order_status)) == 3){
                $result[$i]['textColor']= "#FCFAEE"; 
                $result[$i]['title']    = $or->customer_name. "-- 🚫 ".$or->type_name;
            }else if(intval(($or->order_status)) == 4){
                $result[$i]['title']    = $or->customer_name. "-- ❌ ".$or->type_name;
                $result[$i]['textColor']= "#FF2B12"; 
            }
            
            if(intval($or->itm_code)      == 7050 ){
                $result[$i]['color'] = $this->cottage_1;  
            }else if(intval($or->itm_code)== 7051 ){
                $result[$i]['color'] = $this->cottage_2; 
            }
            else if(intval($or->itm_code) == 7052 ){
                $result[$i]['color'] = "#800080"; 
            }
            else if(intval($or->itm_code) == 7053 ){
                $result[$i]['color'] = $this->cottage_4; 
            }else if(intval($or->itm_code)== 7054 ){
                $result[$i]['color'] = "#B45F04"; 
            }else if(intval($or->itm_code)== 7055 ){
                $result[$i]['color'] = "#0431B4"; 
            }
            $result[$i]['status']    = "☉ ".$or->type_name;
            $result[$i]['customer']  = $or->customer_name;
            $i++;
        }
        return $result;
    }

    public function cottage_list(Request $request){
        // $select_list=$request->selected_cottage;
        $cottage_list=[];
        if($request->id == "forCottage"){
            $data=VarItemInfo::leftJoin('5m_sv_uom', 'var_item_info.uom_id', '=', '5m_sv_uom.id')
            ->select('5m_sv_uom.uom_short_code','var_item_info.*')
            ->where('var_item_info.itm_sub_grp_id',config('globals.cottage_room_master_group'))
            // ->whereNotIn('var_item_info.id',$select_list)
            ->get();
            $i=0;
            foreach($data as $dt){
                $cottage_list[$i]['title'] = $dt->display_itm_name;
                if(intval($dt->itm_code) == 7050 ){
                    $cottage_list[$i]['color'] = $this->cottage_1;  
                }else if(intval($dt->itm_code) == 7051 ){
                    $cottage_list[$i]['color'] = $this->cottage_2; 
                }
                else if(intval($dt->itm_code) == 7052 ){
                    $cottage_list[$i]['color'] = "#800080"; 
                }
                else if(intval($dt->itm_code) == 7053 ){
                    $cottage_list[$i]['color'] = $this->cottage_4; 
                }else if(intval($dt->itm_code) == 7054 ){
                    $cottage_list[$i]['color'] = "#B45F04"; 
                }else if(intval($dt->itm_code) == 7055 ){
                    $cottage_list[$i]['color'] = "#0431B4"; 
                }
                $i++;
            }
        }else{
            $cottage_list=VarItemInfo::leftJoin('5m_sv_uom', 'var_item_info.uom_id', '=', '5m_sv_uom.id')
            ->select('5m_sv_uom.uom_short_code','var_item_info.*')
            ->where('var_item_info.itm_sub_grp_id',config('globals.cottage_room_master_group'))
            // ->whereNotIn('var_item_info.id',$select_list)
            ->get();
        }
        
        $order_status= OrderStatus::all();
        return response()->json([
            "cottage_list"  => $cottage_list,
            "status_list"   => $order_status
        ]);
    }


   public function contact_info_get($id){
        return CsCustomerDetails::select('cs_customer_details.*','cs_customer_contact_info.phone','cs_customer_contact_info.contact_person')
        ->leftJoin('cs_customer_contact_info','cs_customer_details.id','cs_customer_contact_info.customer_id')->where('phone',$id)->first();
   }

   public function cottage_booked_check(Request $request){
        $first_date = date('Y-m-d',strtotime($request->cottage_date['start'])) ;
        $menu_id=$request->menu_id;
        $last_date  = date('Y-m-d',strtotime($request->cottage_date['end']));
        return DB::table('trns00h1_order_child_date')
        ->leftJoin('trns00h_order_child','trns00h_order_child.id','trns00h1_order_child_date.order_child_id')
        ->whereBetween('from_date', [$first_date, $last_date])
        ->whereBetween('to_date', [$first_date, $last_date])
        ->where('trns00h_order_child.item_info_id',$menu_id)
        ->get();
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
        $validated = $request->validate([
            'customer_id'       => 'required|exists:cs_customer_details,id',
            'number_of_guest'   => 'required',
            'remarks'           => 'nullable|max:255',
        ]);
        try {
            DB::beginTransaction();
            $total_discount = $request->tota_discount;
            $total_vat      = collect($request->services)->sum('vat_amt');

            $witout_vat_total_amt = $request->total_after_dis - $total_vat;
           
            $order=OrderMaster::create([
                    'store_id'                  => 23,
                    'created_by'                => Auth::id(),
                    'order_date'                => now(),
                    'number_of_guest'           => $request->number_of_guest,
                    'customer_id'               => $request->customer_id,
                    'customer_phone'            => $request->phone_number,
                    'total_amount_without_vat'  => $witout_vat_total_amt,
                    'total_discount_amount'     => $request->tota_discount,
                    'total_amount_with_vat'     => $request->total_after_dis,
                    'total_vat_amount'          => $total_vat,
                    'order_status'              => $request->status,
                    'payable'                   => $request->total_after_dis,
                    'remarks'                   => $request->remarks
            ]);
             foreach ($request->services as $information) {
               $orderChild= OrderChild::create([
                    "order_master_id"           => $order->id,
                    "branch_id"                 => Auth::user()->branch_id,
                    "item_info_id"              => $information['menu_id'],
                    "uom_id"                    => $information['uom_id'],
                    "uom_short_code"            => $information['uom'],
                    "relative_factor"           => 1,
                    "item_rate"                 => $information['menu_rate'],
                    "discount"                  => $information['dis'],
                    "order_qty"                 => $information['menu_qty'],
                    "item_value_local_curr"     => $information['menu_amount'],
                    "total_amount_local_curr"   => $information['menu_amount'],
                    "vat_percent"               => $information['vat_per'],
                    "vat_amount"                => $information['vat_amt'],
                    "created_by"                => Auth::id(),
                ]);

                $order_Date=Orderchiledate::create([
                    "order_child_id"            => $orderChild->id,
                    "from_date"                 => $information['start_date'],
                    "to_date"                   => $information['end_date'],
                    "created_by"                => $orderChild->created_by,
                ]);
            }
            DB::commit();
            return sendJson('cottage Has Been Created', $order, 200);
        } catch (\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
            return sendJson('cottage Create Failed', $exp->getMessage(), 400);
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
        return OrderChild::leftJoin('trns00h1_order_child_date','trns00h_order_child.id','trns00h1_order_child_date.order_child_id')
        ->select('trns00h_order_child.id as id','trns00g_order_master.status','trns00g_order_master.id as orderMaster_id','var_item_info.display_itm_name','cs_customer_details.customer_name',
        'trns00h1_order_child_date.from_date','trns00h1_order_child_date.to_date','var_item_info.itm_code','trns00g_order_master.remarks')
        ->leftJoin('trns00g_order_master','trns00g_order_master.id','trns00h_order_child.order_master_id')
        ->leftJoin('var_item_info','var_item_info.id','trns00h_order_child.item_info_id')
        ->leftJoin('cs_customer_details','cs_customer_details.id','trns00g_order_master.customer_id')
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
        // $dd=array();
        // $order_child=OrderChild::find($id);
        // $first_date = date('Y-m-d',strtotime($request->start));
        // $last_date  = date('Y-m-d',strtotime($request->end));
        // $menu_id=$order_child->item_info_id;
        // $dd= Orderchiledate::leftJoin('trns00h_order_child','trns00h_order_child.id','trns00h1_order_child_date.order_child_id')
        // ->where('trns00h_order_child.item_info_id',$menu_id)->get();
        // // return $dd;
        // $filteredItems = collect($dd)->filter(function ($item) use ($first_date, $last_date) {
        //     $fromDate = Carbon::parse($item['from_date']);
        //     $toDate = Carbon::parse($item['to_date']);
        
        //     return ($fromDate->between($first_date, $last_date) || $toDate->between($first_date, $last_date));
        // })->values();
    
        // // return $filteredItems;
        // if(count($filteredItems)<= 1){
            try {
                $data = Orderchiledate::where('order_child_id',$id)->first();
                $data->from_date = date('Y-m-d',strtotime($request->start));
                $data->to_date   = date('Y-m-d',strtotime($request->end));
                $data->save();
                if($request->status){
                    $orderMaster=OrderMaster::find($request->orderMaster_id);
                    $orderMaster->order_status = $request->status;
                    $orderMaster->remarks= $request->remarks;
                    $orderMaster->save();
                }
                
                return response()->json("Successful",200) ;
            } catch (\Throwable $ex) {
                return response()->json([
                    "error" => $ex->getMessage()
                ], 500);
            }
        // }else{
        //     return response()->json("this date already booked ".count($dd),200) ;
        // }
        
        
        
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
