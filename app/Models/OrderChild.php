<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderChild extends Model
{
    use HasFactory;

    public $table = "trns00h_order_child";

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function item_info()
    {
        return $this->belongsTo(VarItemInfo::class, 'id', 'item_info_id');
    }

    public function p_status(){
        return $this->belongsTo(OrderStatus::class, 'process_status', 'id');
    }
}
