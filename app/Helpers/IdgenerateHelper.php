<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class IdgenerateHelper{

    public function invoiceNumber($latest,$val,$pre=null)
    {
        if (! $latest) {
            return $pre.'1';
        }
        $string = preg_replace("/[^0-9\.]/", '', $val);
        return  $pre.$string+1;
    }

    // program_master_table_id generate

    public function proMaster_id_gen(){
        $latest = DB::table('p_program_master')->latest()->select('id')->first();
        if($latest){
            $invoice=$latest->id;
        }else{
            $invoice=null;
        }
      
        $cusID = $this->invoiceNumber($latest,$invoice);
        return $cusID;
    }
    public function proCard_id_gen(){
        $latest = DB::table('p_program_card')->latest()->select('card_id')->first();
        if($latest){
            $invoice=$latest->card_id;
        }else{
            $invoice=null;
        }
      
        $cusID = $this->invoiceNumber($latest,$invoice);
        return $cusID;
    }
}
