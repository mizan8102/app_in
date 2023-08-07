<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierDetail extends Model
{
    use HasFactory;

    public $table = "cs_supplier_details";

    protected $primaryKey = "id";

    protected $guarded=[];

    public function supplier_mapping()
    {
        return $this->belongsTo(SupplierMapping::class, 'sup_id', 'supplier_id');
    }
    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class, 'supplier_id', 'supplier_id');
    }
    /**
     * Get all of the comments for the SupplierDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recvMaster(): HasMany
    {
        return $this->hasMany(RecvMaster::class, 'supplier_id', 'id');
    }
}
