<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMaster extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='trns50a_payment_master';
    protected $guarded=[];
}
