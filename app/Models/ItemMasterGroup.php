<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemMasterGroup extends Model
{
    use HasFactory;

    protected $table = "var_item_master_group";
    protected $primaryKey = "id";
    protected $guarded = [];
    /**
     * Get all of the comments for the ItemMasterGroup
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productGroups(): HasMany
    {
        return $this->hasMany(ProductGroup::class, 'itm_mstr_grp_id', 'id');
    }
   
}
