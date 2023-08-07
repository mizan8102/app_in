<?php

namespace App\Models;

use App\Models\PaymentToSupplierChild;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PaymentToSupplierMaster extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'trns52a_pay_to_sup_master';
    protected $primary_key = 'id';
    /**
     * Get all of the comments for the PaymentToSupplierMaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function item_row(): HasMany
    {
        return $this->hasMany(PaymentToSupplierChild::class, 'master_id', 'id');
    }
    public function supplier():HasOne{
        return $this->hasOne(SupplierDetail::class,'id','supplier_id');
    }
    
}