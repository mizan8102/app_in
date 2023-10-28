<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use App\Repositories\StoredProcedureRepositoryInterface;
use DB;
use Illuminate\Http\Request;

class ReportApiController extends Controller
{
   protected $storedProcedureRepository;

   public function __construct(StoredProcedureRepositoryInterface $storedProcedureRepository){
       $this->storedProcedureRepository = $storedProcedureRepository;
   }
   


   // Item wise daily sell grid 
    public function itemWise(Request $request){
        $from =  Date('Y-m-d',strtotime($request->from));
        $to =  Date('Y-m-d',strtotime($request->to));
        
        if(isset($request->from) && isset($request->to)){
           return DB::select('CALL Report_A_01_ItemWiseDailySell("'.$from.'","'.$to.'")');
          
        }else{
           return [];
        }
    }




    // Order wise daily sell grid
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



    // Issue details grid 
    public function issueDetails(Request $request){
        $issueIds=$request->no;
        if(isset($issueIds)){
            return DB::select('CALL Report_C_04A_IssueDetails("'.$issueIds.'")');
            
        }else{
            return response()->json(['data' =>[]]) ;
        }
    }


    // indent report
    public function indentReport(Request $request){
        $CloseStatus=0;
        $IsProductReq=0;
        $indents = DB::select('CALL GetIndentID("'.$CloseStatus.'","'.$IsProductReq.'")');
        if(isset($request->no)){
            return  DB::select('CALL Report_B_01A_IndentReport("'.$request->no.'")');
            
        }else{
            return response()->json(['data' =>[]]) ;
        }
    }



    // Issue report
    public function issueReturnGrid(Request $request){
        if(isset($request->no)){
            return  DB::select('CALL Report_C_05A_IssueReturn("'.$request->no.'")');
        }else{
            return response()->json(['data' =>[]]) ;
        }
    }


    // Purchase Order Summary 
    public function purchasedOrderSummaryGrid(Request $request){

        $from =  Date('Y-m-d',strtotime($request->from));
        $to =  Date('Y-m-d',strtotime($request->to));

        if(isset($from) && isset($to)){
           return DB::select('CALL Report_B_04B_PurchaseOrderSummary("'.$from.'","'.$to.'")');
           
        }else{
            return response()->json(['data' =>[]]) ;
        }
        
    }

    // Issue Summary 
    public function issueSummaryGrid(Request $request){
        $from =  Date('Y-m-d',strtotime($request->from));
        $to =  Date('Y-m-d',strtotime($request->to));
        if(isset($request->from) && isset($request->to)){
            return DB::select('CALL Report_C_04B_IssueSummary("'.$from.'", "'.$to.'","'.$request->no.'")');
            
            
        }else{
            return response()->json(['data' =>[]]) ;
        }
    }




    // Receive Summary 
    public function receiveSummaryGrid(Request $request){

        $from =  Date('Y-m-d',strtotime($request->from));
        $to =  Date('Y-m-d',strtotime($request->to));
        if(isset($from) && isset($to)){
            return DB::select('CALL Report_C_03B_ReceiveSummary("'.$from.'","'.$to.'")');
            
        }else{
            return response()->json(['data' =>[]]) ;
        }
    }


    public function initProcedure($procedureName){
        return $this->storedProcedureRepository->callStoredProcedure($procedureName);
    }

    // order wise daily sell 
    public function orderWiseDailySellGrid(Request $request){
        $from =  Date('Y-m-d',strtotime($request->from));
        $to =  Date('Y-m-d',strtotime($request->to));

        if(isset($from) && isset($to)){
            return DB::select('CALL Report_A_02_OrderWiseDailySell("'.$from.'", "'.$to.'","'.$request->no.'")');
            
        }else{
            return response()->json(['data'=>[]]) ;
        }
    }


    // Product requisition summary 
    public function productRequisitionSummaryGrid(Request $request){
        $from =  Date('Y-m-d',strtotime($request->from));
        $to =  Date('Y-m-d',strtotime($request->to));

        if(isset($from) && isset($to)){
            return DB::select('CALL Report_B_02B_ProductRequisitionSummary("'.$from.'", "'.$to.'")');
            
        }else{
            return response()->json(['data'=>[]]) ;
        }
    }
    // Purchase requisition summary 
    public function purchaseRequisitionSummaryGrid(Request $request){
        $from =  Date('Y-m-d',strtotime($request->from));
        $to =  Date('Y-m-d',strtotime($request->to));
        if(isset($from) && isset($to)){
            return DB::select('CALL Report_B_03B_PurchaseRequisitionSummary("'.$from.'", "'.$to.'")');
            
        }else{
            return response()->json(['data'=>[]]) ;
        }
    }


    // issue Return Summary 
    public function issueReturnSummaryGrid(Request $request){
        $from = Date('Y-m-d',strtotime($request->from));
        $to = Date('Y-m-d',strtotime($request->to));
        if(isset($from) && isset($to)){
            return DB::select('CALL Report_C_05C_IssueReturnSummary("'.$from.'", "'.$to.'")');       
        }else{
            return response()->json(['data'=>[]]) ;
        }
    }

    // 26-10-23 waiterWiseDailySellGrid
    public function waiterWiseDailySellGrid(Request $request){
        $from = Date('Y-m-d',strtotime($request->from));
        $to = Date('Y-m-d',strtotime($request->to));

        if(isset($from) && isset($to) && isset($request->no)){
            return DB::select('CALL Report_A_03_WaiterWiseDailySell("'.$from.'","'.$to.'","'.$request->no.'")');   
         }else{
            return response()->json(['data'=>[]]) ;
        }    
    }

    // 26-10-23 purchaseRequisionSummaryGrid
    public function purchaseRequisionSummaryGrid(Request $request){
        $from = Date('Y-m-d',strtotime($request->from));
        if(isset($from)){
            return DB::select('CALL Report_BB_B_03C_PurchaseRequisitionSummary("'.$from.'")');   
         }else{
            return response()->json(['data'=>[]]) ;
        }   
    }

    // 26-10-23 costingConsumptionSummaryGrid

    public function costingConsumptionSummaryGrid(Request $request){
        if(isset($request->no)){
            return DB::select('CALL Report_C_01B_CostingConsumptionSummary("'.$request->no.'")');   
         }else{
            return response()->json(['data'=>[]]) ;
        }   
    }

    // 26-10-23 C_05B issueReturnByProgramGrid
    public function issueReturnByProgramGrid(Request $request){
        if(isset($request->no)){
            return DB::select('CALL Report_C_05B_IssueReturnByProgram("'.$request->no.'")');   
         }else{
            return response()->json(['data'=>[]]) ;
        }   
    }

}
