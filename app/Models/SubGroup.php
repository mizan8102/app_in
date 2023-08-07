<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubGroup extends Model
{
    use HasFactory;
    protected $table = "var_item_sub_group";
    protected $primary_key = "id";
    protected $guarded = [];

    public function product_group()
    {
        return $this->belongsTo(ProductGroup::class, 'itm_grp_id', 'id');
    }

    public function var_item_info()
    {
        return $this->hasMany(VarItemInfo::class, 'itm_sub_grp_id', 'id');
    }
}
