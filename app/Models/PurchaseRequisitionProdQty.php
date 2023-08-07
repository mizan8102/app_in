<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequisitionProdQty extends Model
{
    use HasFactory;
    protected $table = 'trns00d1_purchase_req_prod_req_qty';
    protected $primary_key='id';
    protected $guarded=[];

}
