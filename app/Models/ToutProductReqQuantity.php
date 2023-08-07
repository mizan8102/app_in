<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToutProductReqQuantity extends Model
{
    use HasFactory;
    protected $table= 'trns06b1_transferout_prod_req_qty';
    protected $primary_key='id';
    protected $guarded=[];
}