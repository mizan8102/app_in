<?php

namespace App\Helpers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Helper
{
    public static function sendJson(array | object $arr) : array | object{
        return response()->json($arr);
    }

    public static function codeGenerate(string $table_name):string{
        $comID = Auth::user()->company_id;
        $data = DB::select('CALL getTableID("'.$table_name.'","' .$comID.'")');
        return $data[0]->masterID;
    }

    public static function master_group_wise_item_count($item):array{
        $result=collect($item);
        $grouped = $result->groupBy('itm_mstr_grp_name')->map(function ($items, $master_group_id) {
            return [
                'master_group' => $master_group_id,
                'master_group_id'=>$items[0]->master_group_id,
                'prod_type_id'=>$items[0]->prod_type_id,
                'prod_cat_id'=>$items[0]->prod_cat_id,
                'backgroundColor'=> self::generateRandomColor(),
                'count' => $items->count(),
                'data' => $items->pluck('id'),
            ];
        })->values()->all();
        return $grouped;
    }


    public static function generateRandomColor() {
        $red = mt_rand(0, 127);
        $green = mt_rand(0, 127);
        $blue = mt_rand(0, 127);
        return "rgba($red, $green, $blue)";
    }
}
