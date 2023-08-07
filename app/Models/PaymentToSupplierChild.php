<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PaymentToSupplierChild extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'trns52b_pay_to_sup_child';
    protected $primary_key = 'id';
    public function receivemaster():HasOne{
        return $this->hasOne(RecvMaster::class,'id','recv_master_id');
    }
}
