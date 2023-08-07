<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IOCPriceDeclaration extends Model
{
    use HasFactory;

    public $table = "tran01a_ioc_price_declaration";

    protected $primaryKey = 'id';
    protected $guarded=[];

//    protected $fillable = [
//        'company_id',
//        'quantity',
//        'prc_decl_name',
//        'prc_decl_number',
//        'effective_from',
//        'item_information_id',
//        'is_manufactured_itm',
//        'total_cost_rm',
//        'total_overhead_cost',
//        'total_monthly_srv_cost',
//        'total_cost',
//        'date_of_submission',
//        'approved_by_nbr',
//        'remarks',
//        'created_by',
//        'updated_by',
//    ];

    public function ioc_item_info(){
        return $this->belongsTo(VarItemInfo::class, 'item_information_id', 'id');
    }

    public function itemInfoRows(){
        return $this->hasMany(IOCItemDetail::class, 'ioc_price_declaration_id', 'id');
    }

    public function inputServiceRows(){
        return $this->hasMany(IOCInputService::class, 'ioc_price_declaration_id', 'id');
    }

    public function valueAddedRows(){
        return $this->hasMany(IOCValueAddedService::class, 'ioc_price_declaration_id', 'id');
    }
}
