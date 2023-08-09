<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
   
    public function indent(){
        return $this->hasMany(ItemMasterModel::class, 'master_group_id','id');
    }
}
