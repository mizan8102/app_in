<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderQty extends Model
{
    use HasFactory;

    protected $fillable=[
        'purchase_order_child_id',
        'purchase_order_master_id',
        'item_information_id',
        'receive_quantity'
    ];
}
