<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReceiveMapping extends Model
{
    use HasFactory;
    protected $fillable=[
        'receive_master_id',
        'purchase_order_child_id',
        'item_information_id',
        'receive_quantity'
    ];
}
