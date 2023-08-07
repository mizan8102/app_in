<?php

namespace App\Models;

use App\Models\CsCompanyStoreLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $primary_key='id';
    protected $table="trns00e_purchase_order_master";
    protected $guarded=[];

    public function purchase_order_child(){
        return $this->hasMany(PurchaseOrderChild::class,'purchase_order_master_id','id');
    }

    public function supplierDetail(){
        return $this->hasMany(SupplierDetail::class,'id', 'supplier_id');
    }
    /**
     * Get all of the comments for the PurchaseOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(CsCompanyStoreLocation::class, 'delivery_point', 'id');
    }
}
