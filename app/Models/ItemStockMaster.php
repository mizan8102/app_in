<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ItemStockMaster extends Model
{
    use HasFactory;
    protected $primary_key="itemstock_master_id";
    protected $table="trns_itemstock_master";
    protected $guarded=[];
    /**
     * Get all of the comments for the ItemStockMaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childStock(): HasMany
    {
        return $this->hasMany(ItemChildModel::class, 'id', 'indent_master_id');
    }
    public function currency(){
        return $this->hasOne(Currency::class,'id','currency_id');
    }
    public function childStockOp(){
        return $this->hasOne(ItemStockChild::class,'itemstock_master_id','itemstock_master_id');
    }
    /**
     * Get all of the comments for the ItemStockMaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itemInfo(): HasOne
    {
        return $this->hasOne(VarItemInfo::class, 'id', 'item_information_id');
    }
}
