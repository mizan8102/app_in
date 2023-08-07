<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IOCInputService extends Model
{
    use HasFactory;

    public $table = "tran01c_ioc_input_service";

    protected $primaryKey = 'id';
    protected $guarded=[];

//    protected $fillable = [
//        'ioc_price_declaration_id',
//        'input_service_id',
//        'input_service_amount',
//        'created_by',
//    ];

    public function service_info(){
        return $this->belongsTo(InputService::class, 'input_service_id', 'input_service_id');
    }
}
