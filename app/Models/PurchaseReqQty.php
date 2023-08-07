<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReqQty extends Model
{
    use HasFactory;

    protected $table = "trns00f1_po_master_prod_child_qty";
    protected $guarded = [];
}
