<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use App\Repositories\StoredProcedureRepositoryInterface;
use DB;
use Illuminate\Http\Request;

class ReportApiController extends Controller
{
   protected $storedProcedureRepository;

   public function __construct(StoredProcedureRepositoryInterface $storedProcedureRepository)
   {
       $this->storedProcedureRepository = $storedProcedureRepository;
   }
   
    public function itemWise(Request $request)
    {
        // return $request;
        $from =  Date('Y-m-d',strtotime($request->from));
        $to =  Date('Y-m-d',strtotime($request->to));
        
        if(isset($request->from) && isset($request->to)){
           return DB::select('CALL Report_A_01_ItemWiseDailySell("'.$from.'","'.$to.'")');
          
        }else{
           return [];
        }
    }

    public function A_02_order_wise_daily(Request $request){
        $from =  Date('Y-m-d',strtotime($request->from));
        $to =  Date('Y-m-d',strtotime($request->to));
        $store= $request->no;
        if(isset($request->from) && isset($request->to)){
           return DB::select('CALL Report_A_02_OrderWiseDailySell("'.$from.'","'.$to.'","'.$store.'")');
          
        }else{
           return response()->json(['data' =>[]]) ;
        }
    }


    // indent 

    public function initProcedure($procedureName)
    {
        return $this->storedProcedureRepository->callStoredProcedure($procedureName);
    }




}
