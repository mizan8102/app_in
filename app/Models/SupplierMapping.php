<?php

namespace App\Models;

use App\Models\VarItemInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierMapping extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function supplier_detail()
    {
        return $this->hasMany(SupplierDetail::class, 'id', 'sup_id');
    }
    /**
     * Get the user associated with the SupplierMapping
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function itemInfo(): HasOne
    {
        return $this->hasOne(VarItemInfo::class, 'item_id', 'id');
    }
}
