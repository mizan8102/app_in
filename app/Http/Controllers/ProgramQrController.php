<?php

namespace App\Http\Controllers;

use App\Models\PProgramCard;
use App\Models\PProgramMaster;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProgramQrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $data=PProgramCard::where('program_master_id',$id)->get();
        $ar=[];
        $codes=PProgramCard::where('program_master_id',$id)->get();
        foreach($codes as $key => $value){
            $hp=strval(QrCode::size(50)->generate($value['card_id']));
            array_push($ar,$hp);
        }
        return $ar;

    }

    public function printShow($id,$token)
    {
           $result= Http::withHeaders([
            'Authorization' => 'Bearer '.$token,
            ])->get('http://45.94.209.231/chiklee_new/chiklee_api/public/api/printDataApi/'.$id)->json();

            if($result['is_print'] ==0){
                $data = DB::table('p_program_master')->where('id', $id)->update(['is_print' => 1]);
                $data = DB::table('p_program_card')->where('program_master_id', $id)->update(['print_status' => 1]);

                 return view('printQR',compact('result'));
            }else{
                
            }
                 
    }
    public function printDataApi($id){
        try {
                $data = DB::table('p_program_master')
                    // ->leftJoin('cs_company_store_location','p_program_master.restaurant_master_id','=','cs_company_store_location.store_id')
                    // ->leftJoin('r_floor','p_program_master.floor_id','=','r_floor.id')
                    // ->leftJoin('cs_customer_details','p_program_master.customer_id','=','cs_customer_details.id')
                    // ->leftJoin('r_program_type','p_program_master.program_type_id','=','r_program_type.id')
                    // ->leftJoin('p_program_menu','p_program_master.id','=','p_program_menu.program_master_id')
                    ->leftJoin('p_program_card','p_program_master.id','=','p_program_card.program_master_id')
                    // ->leftJoin('var_item_info','p_program_menu.menu_id','=','var_item_info.item_information_id')
                    // ->leftJoin('5m_sv_uom','var_item_info.uom_id','=','5m_sv_uom.uom_id')
                    // ->leftJoin('users','p_program_master.created_by','=','users.id')
                    // ->leftJoin('program_pay_details','p_program_master.id','=','program_pay_details.p_program_master_id')
                    ->select('p_program_master.*',
                    'p_program_master.id AS ppid',
                                    // 'cs_company_store_location.sl_name',
                                    // 'cs_company_store_location.sl_name_bn',
                                    // 'r_floor.floor_name',
                                    // 'r_floor.floor_name_bn',
                                    // 'cs_customer_details.customer_name',
                                    // 'cs_customer_details.phone_number',
                                    // 'cs_customer_details.customer_name_bn',
                                    // 'r_program_type.program_type_name',
                                    // 'r_program_type.program_type_name_bn',
                                    // 'p_program_menu.id AS p_menu_id',
                                    // 'p_program_menu.menu_id AS menu_id',
                                    // 'p_program_menu.menu_qty AS menu_qty',
                                    // 'p_program_menu.display_itm_name AS display_itm_name',
                                    // 'p_program_menu.menu_rate AS menu_rate',
                                    // 'p_program_menu.menu_amount AS menu_amount',
                                    // 'var_item_info.display_itm_name',
                                    // 'var_item_info.display_itm_name_bn',
                                    // '5m_sv_uom.uom_id',
                                    // '5m_sv_uom.uom_short_code',
                                    // '5m_sv_uom.relative_factor',
                                    'p_program_card.id AS p_card_id',
                                    'p_program_card.card_id AS card_id',
                                    'p_program_card.use_status AS use_status',
                                    // 'program_pay_details.total_amount',
                                    // 'program_pay_details.paid_amount',
                                    // 'program_pay_details.due_amount',
                                    // 'users.name AS username'

                                    )
                    ->where('p_program_master.id',$id)->where('p_program_card.print_status',0)->get();
                $Data = collect($data);
                $result=[];
                $i=0;

                foreach ($data as $i=>$item) {
                    if ($i == 0) {
                        $result['id'] = $item->ppid;
                        // $result['store_id'] = $item->restaurant_master_id;
                        // $result['sl_name'] = $item->sl_name;
                        // $result['sl_name_bn'] = $item->sl_name_bn;
                        // $result['floor_id'] = $item->floor_id;
                        
                        // $result['total_amount_without_vat'] = $item->total_amount_without_vat;
                        // $result['vat_on_food'] = $item->vat_on_food;
                        // $result['food_vat_per'] = $item->food_vat_per;
                        // $result['vat_on_food'] = $item->vat_on_food;
                        // $result['username'] = $item->username;
                        // $result['paid_amount'] = $item->paid_amount;
                        // $result['remarks'] = $item->remarks;
                        // $result['due_amount'] = $item->due_amount;
                        // $result['hall_room_charge_vat_per'] = $item->hall_room_charge_vat_per;
                        
                    
                        // $result['date'][0]=$item->prog_start_time;
                        // $result['date'][1]=$item->prog_end_time;
                        
                        // $result['floor_name'] = $item->floor_name;
                        // $result['floor_name_bn'] = $item->floor_name_bn;
                        // $result['customer_id'] = $item->customer_id;
                        // $result['customer_name'] = $item->customer_name;
                        // $result['phone'] = "0".$item->phone_number;
                        // $result['customer_name_bn'] = $item->customer_name_bn;
                        // $result['program_type_id'] = $item->program_type_id;
                        // $result['program_type_name'] = $item->program_type_name;
                        // $result['program_type_name_bn'] = $item->program_type_name_bn;
                        // $result['program_name'] = $item->program_name;
                        // $result['program_name_bn'] = $item->program_name_bn;
                        // $result['prog_date'] = $item->prog_date;
                        // $result['prog_start_time'] = $item->prog_start_time;
                        // $result['prog_end_time'] = $item->prog_end_time;
                        // $result['number_of_guest'] = $item->number_of_guest;
                        // $result['hall_room_charge'] = $item->hall_room_charge;
                        // $result['hall_room_vat'] = $item->hall_room_vat;
                        // $result['food_charge'] = $item->food_charge;
                        // $result['total_amount'] = $item->total_amount;
                        // $result['vat_amount'] = $item->vat_amount;
                        // $result['total_amount_with_vat'] = $item->total_amount_with_vat;                    
                        // $result['is_active'] = $item->is_active;
                        // $result['is_print'] = $item->is_print;
                        // $result['created_at'] = $item->created_at;
                        // $result['updated_at'] = $item->updated_at;
                        // $result['created_by'] = $item->created_by;
                        // $result['updated_by'] = $item->updated_by;

                        // $menuData = $Data->where('id',$item->id)->groupBy('p_menu_id');
                        // $m=0;
                        // foreach ($menuData as $itm=>$itmInfo) {
                        //     $result['program_childs'][$m]['p_menu_id'] = $itmInfo[0]->p_menu_id;
                        //     $result['program_childs'][$m]['menu_id'] = $itmInfo[0]->menu_id;
                        //     $result['program_childs'][$m]['display_itm_name'] = $itmInfo[0]->display_itm_name;
                        //     $result['program_childs'][$m]['menu_name_bn'] = $itmInfo[0]->display_itm_name_bn;
                        //     $result['program_childs'][$m]['menu_qty'] = $itmInfo[0]->menu_qty;
                        //     $result['program_childs'][$m]['menu_rate'] = $itmInfo[0]->menu_rate;
                        //     $result['program_childs'][$m]['menu_amount'] = $itmInfo[0]->menu_amount;
                        //     $result['program_childs'][$m]['uom_id'] = $itmInfo[0]->uom_id;
                        //     $result['program_childs'][$m]['uom'] = $itmInfo[0]->uom_short_code;
                        //     $result['program_childs'][$m]['relative_factor'] = $itmInfo[0]->relative_factor;
                        //     $m++;
                        // }
                        $cardData = $Data->where('id',$item->id)->groupBy('p_card_id');
                        $k=0;
                        foreach ($cardData as $itm=>$itmInfo) {
                            $result['card_item'][$k]['p_card_id'] = $itmInfo[0]->p_card_id;
                            $result['card_item'][$k]['card_id'] = $itmInfo[0]->card_id;
                            $result['card_item'][$k]['use_status'] = $itmInfo[0]->use_status;
                            // $result['card_item'][$k]['card_number'] = $itmInfo[0]->card_number;
                            $k++;
                            // paid_amount
                        }
                    }
                }
              return $result;
                // return $result;
            } catch (QueryException $ex) {
            return response()->json([
                "dd" => $ex->getMessage()
            ]);
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

    public function isPrintQr($id,$isPrint){
        $test['is_print']=1;
        PProgramMaster::where('id',$id)->update($test);
    }
}
