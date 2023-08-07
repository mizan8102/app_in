<?php

namespace App\Http\Controllers\Khajna;

use App\Http\Controllers\Controller;
use App\Models\SvUOM;
use App\Models\VarItemInfo;
use App\Models\VATMonth;
use App\Models\VatStructureRate;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
class ItemReceivedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    function item_information_for_receive()
    {
        try{
            $itm = VarItemInfo::with('trns_uom')
            ->join('var_item_mapping_bin_prodtype', 'var_item_info.id', '=', 'var_item_mapping_bin_prodtype.item_info_id')
            ->leftJoin('var_vat_structure_rates', 'var_item_info.default_vat_rate_id', '=', 'var_vat_structure_rates.id')
            ->leftJoin('5d4_sv_vat_payment_method','var_item_info.vat_payment_method_id','5d4_sv_vat_payment_method.id')
            ->leftJoin('var_item_sub_group','var_item_sub_group.id','=','var_item_info.itm_sub_grp_id')
            ->leftJoin('var_item_group', 'var_item_group.id', 'var_item_sub_group.itm_grp_id')
            ->leftJoin('var_item_master_group', 'var_item_group.itm_mstr_grp_id', 'var_item_master_group.id')
            ->leftJoin('5f_sv_product_type','5f_sv_product_type.id','var_item_master_group.prod_type_id')
            ->leftJoin('5m_sv_uom','5m_sv_uom.id','var_vat_structure_rates.fixed_rate_uom_id')
            ->leftJoin('5m_sv_uom as sv_uom','sv_uom.id','var_item_info.trns_unit_id')
            ->select('5m_sv_uom.uom_short_code','5m_sv_uom.relative_factor as fixed_rate_relative_factor','var_item_info.id as item_info_id',
            'var_item_info.display_itm_name_bn','var_item_info.trns_unit_id','default_vat_rate_id','sd','vat',
            DB::raw('ifNULL(cd,0) as cd'),'at',DB::raw('ifNULL(ait,0) as ait'),
            'rd',DB::raw('IFNULL(atv,0) as atv'),DB::raw('IFNULL(exd,0) as exd')
            ,DB::raw('IFNULL(tti,0) as tti'),DB::raw('IFNULL(vds,0) as vds'),
            'fixed_rate','fixed_rate_uom_id','vat_payment_method_name','vat_payment_method_id',
            'sv_uom.relative_factor')
            ->where('5f_sv_product_type.id', 2)
            ->where('var_item_mapping_bin_prodtype.store_id', Auth::user()->store_id)
            ->get();
            $svUOM = SvUOM::all();
            $result = [];
            foreach ($itm as $key => $it) {
                $result[$key]['item_info_id'] = $it->item_info_id;
                $result[$key]['itm_name'] = $it->display_itm_name_bn;
                $result[$key]['uom_id'] = $it->trns_unit_id;
                $result[$key]['relative_factor'] = $it->trns_uom['relative_factor'];
                
    
                // vat structure rate 
                $result[$key]['default_vat_rate_id'] = $it->default_vat_rate_id;
                $result[$key]['cd'] = $it->cd;
                $result[$key]['sd'] = $it->sd;
                $result[$key]['vat'] = $it->vat;
                $result[$key]['at'] = $it->at;
                $result[$key]['ait'] = $it->ait;
                $result[$key]['rd'] = $it->rd;
                $result[$key]['atv'] = $it->atv;
                $result[$key]['exd'] = $it->exd;
                $result[$key]['tti'] = $it->tti;
                $result[$key]['vds'] = $it->vds;

                $result[$key]['fixed_rate'] = $it->fixed_rate;
                $result[$key]['fixed_rate_uom_id'] = $it->fixed_rate_uom_id;
                $result[$key]['fixed_rate_uom'] = $it->uom_short_code;
                $result[$key]['fixed_rate_relative_factor'] = $it->trns_uom['relative_factor'];
                // vat payment method 
                $result[$key]['vat_payment_method_name'] = $it->vat_payment_method_name;
                $result[$key]['vat_payment_method_id'] = $it->vat_payment_method_id;
                $result[$key]['uom_set_id'] = $it->trns_uom;
                $k = 0; // Move this outside the inner loop
                foreach ($svUOM as $u) {
                    if ($it->trns_uom['uom_set_id'] == $u['uom_set_id']) {
                        $result[$key]['uoms'][$k]['value'] = $u['id'];
                        $result[$key]['uoms'][$k]['label'] = $u['uom_short_code'];
                        $result[$key]['uoms'][$k]['set_id'] = $u['uom_set_id'];
                        $k++;
                    }
                }
            }
            return $result;
        }catch(Exception $e){
            return $e;
            throw new Exception($e);
            
        }
       
    }


    function vatStructureRetes(){
        return VatStructureRate::leftJoin('5j2_sv_vat_rate_type','5j2_sv_vat_rate_type.id','vat_rate_type_id')
        ->select('var_vat_structure_rates.id as value','5j2_sv_vat_rate_type.vat_rate_type_name_bn as label')->get();
    }

    public function initData(){
        try{
             return response()->json([
                'vat_structure_rates' => $this->vatStructureRetes(),
                'row_materials' => $this->item_information_for_receive(),
                'currencyList'=>DB::table('5n_sv_currency_info')
                ->select('5n_sv_currency_info.*','5p_sv_currency_exc_rate.exch_rate')
                ->leftJoin('5p_sv_currency_exc_rate','5p_sv_currency_exc_rate.currency_info_id','5n_sv_currency_info.id')
                ->where('5n_sv_currency_info.id','1')->get(),
                'supplierList'=>DB::table('cs_supplier_details')->select('id as value','supplier_name as label')->get(),
                'rebateTypeList'=>DB::table('5b_sv_vat_rebate_type')->select('id as value','vat_rebate_name as label')->get(),
                'challanType' => DB::table('5q_sv_challan_type')->select('id as value','challan_type_name as label')->where('is_active',1)->get()
            ]);
        }catch(\Exception $ex){
            return response()->json([
                "status" => "error",
                "message" => $ex,
            ],400);
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

    public function getVatMonthfinanacialYear($date){
        return VATMonth::leftJoin('5x1_fiscal_year_info','5x1_fiscal_year_info.id','5x2_vat_month_info.fy_id')
        ->select('5x2_vat_month_info.id as vat_month_id','5x2_vat_month_info.*','5x1_fiscal_year_info.*')
        ->get();
    }
}
