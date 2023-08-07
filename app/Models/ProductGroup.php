<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductGroup extends Model
{
    use HasFactory;
    protected $table = "var_item_group";
    protected $primary_key = "id";

    protected $guarded = [];

    public function sub_group()
    {
        return $this->hasMany(SubGroup::class, 'itm_grp_id', 'id');
    }
    /**
     * Get the user that owns the ProductGroup
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function masterGroup(): BelongsTo
    {
        return $this->belongsTo(ItemMasterGroup::class, 'itm_mstr_grp_id', 'id');
    }
}
