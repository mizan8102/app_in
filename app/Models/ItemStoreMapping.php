<?php

namespace App\Models;

use App\Models\ProductType;
use App\Models\VarItemInfo;
use App\Models\CsCompanyStoreLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemStoreMapping extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $primary_key = 'id';
    protected $table = 'var_item_mapping_bin_prodtype';
    public function item()
    {
        return $this->belongsTo(VarItemInfo::class, 'item_information_id', 'id');
    }
    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'prod_type_id', 'id');
    }
    public function store()
    {
        return $this->belongsTo(CsCompanyStoreLocation::class, 'store_id', 'id');
    }
}