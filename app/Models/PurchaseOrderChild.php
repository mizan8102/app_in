<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderChild extends Model
{
    use HasFactory;
    protected $primary_key = 'id';
    protected $table = "trns00f_purchase_order_child";
    protected $guarded=[];
    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_master_id', 'id');
    }
    

    public function itemInfo()
    {
        return $this->hasMany(VarItemInfo::class, 'id', 'item_information_id');
    }
}