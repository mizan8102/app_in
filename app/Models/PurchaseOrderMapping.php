<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderMapping extends Model
{
    use HasFactory;
    protected $fillable=[
        'purchase_order_master_id',
        'purchase_req_child_id',
        'item_information_id',
        'order_quantity',
    ];
}
