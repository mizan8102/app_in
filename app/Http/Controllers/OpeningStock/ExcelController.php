<?php

namespace App\Http\Controllers\OpeningStock;

use App\Exports\OpeningStock\OpeningStockExport;
use App\Http\Controllers\Controller;
use App\Models\SvUOM;
use App\Models\VarItemInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class ExcelController extends Controller
{
    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'excel' => 'required|file|mimes:csv,xlsx,xls',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }
        $file = $request->file('excel');
        $data =  Excel::toArray([], $file)[0];
        $result=array();
        $i=0;
        foreach ($data as $row)
        {
            $result[$i]['name'] =trim($row[0], " ") ;
            $result[$i]['qty'] =trim($row[1]," ") ;
            $result[$i]['price'] =trim($row[2]," ") ;
            $i++;
        }
        $var=[];
        $j=0;
        foreach($result as $res){
            $item = VarItemInfo::with('sub_group','sub_group.product_group')->select('var_item_info.*','uom_short_code')
                ->leftJoin('5m_sv_uom','5m_sv_uom.id','=','var_item_info.uom_id')
                ->where('var_item_info.display_itm_name', $res['name'])->first();
            if($item ){
                $var[$j]['status']= 1;
                $var[$j]['item_information_id']= $item->id;
                $var[$j]['display_itm_name']=$item->display_itm_name;
                $var[$j]['uom_id']=$item->uom_id;
                $var[$j]['uom_short_code']=$item->uom_short_code;
                $var[$j]['opening_bal_qty']=$res['qty'];
                $var[$j]['opening_bal_rate']=$res['price'];
                $var[$j]['opening_bal_amount']=$res['qty'] * $res['price'];
                $var[$j]['sub_grp_id']=$item->sub_group;
                $var[$j]['group_id']=$item->sub_group->product_group;
                $var[$j]['input']= 0;
                $var[$j]['remarks']= "";
                $j++;
            }else{
                if($res['name'] != 'display_itm_name'){
                    $var[$j]['status']= 0;
                    $var[$j]['display_itm_name']=$res['name'];
                    $var[$j]['opening_bal_qty']=$res['qty'];
                    $var[$j]['opening_bal_rate']=$res['price'];
                    $var[$j]['opening_bal_amount']=0;
                    $var[$j]['input']= 0;
                    $var[$j]['remarks']= "";
                    $j++;
                }
            }


        }
        return response()->json($var, Response::HTTP_OK);
    }
    public function export(Request $request)
    {
        $data = $request->all();
        return Excel::download(new OpeningStockExport($data), 'opening_stock.xlsx');
    }
    public function download(){
        $pathToFile = public_path('excel/opening.xlsx');
        return response()->download($pathToFile);
    }

}
