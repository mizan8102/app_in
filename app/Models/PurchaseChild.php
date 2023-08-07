<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseChild extends Model
{
    use HasFactory;
    protected $primary_key='purchase_order_child_id';
    protected $table="trns00f_purchase_order_child";
    protected $fillable=[
        'purchase_order_master_id',
        'purchase_req_child_id',
        'item_information_id',
        'uom_id',
        'uom_short_code',
        'relative_factor',
        'order_quantity',
        'recv_quantity',
        'rate',
        'total_amount_local_cr',
        'Remarks',
        'Remarks_bn',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];
}
