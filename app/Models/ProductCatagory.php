<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCatagory extends Model
{
    use HasFactory;
    protected $table = "5h_sv_product_category";
    protected $primary_key = "id";
    protected $guarded = [];
    /**
     * Get all of the comments for the ProductCatagory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productTypes(): HasMany
    {
        return $this->hasMany(ProductType::class, 'prod_cat_id', 'id');
    }
}
