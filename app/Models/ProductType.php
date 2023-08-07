<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductType extends Model
{
    use HasFactory;
    protected $table = "5f_sv_product_type";
    protected $primary_key = "id";
    protected $guarded = [];
    /**
     * Get all of the comments for the ProductType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itemMasterGroups(): HasMany
    {
        return $this->hasMany(ItemMasterGroup::class, 'prod_type_id', 'id');
    }
    public function catagory(){
        return $this->belongsToMany(ProductCatagory::class,'5f_sv_product_type','id','prod_cat_id');
    }
}
