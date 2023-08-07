<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IOCValueAddedService extends Model
{
    use HasFactory;

    public $table = "tran01d_ioc_value_adding_svc";

    protected $primaryKey = 'id';
    protected $guarded=[];

//    protected $fillable = [
//        'ioc_price_declaration_id',
//        'value_adding_service_id',
//        'value_adding_service_amount',
//        'created_by',
//    ];

    public function service_info(){
        return $this->belongsTo(ValueAddedService::class, 'value_adding_service_id', 'vas_id');
    }
}
