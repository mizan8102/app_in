<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ItemChildModel extends Model
{
    use HasFactory;
    protected $table='trns00b_indent_child';
    protected $primaryKey="id";
    protected $guarded=[];
    public function itemIndentMaster(){
        return $this->belongsTo(ItemMasterModel::class,'id','indent_master_id');
    }
    /**
     * Get all of the comments for the ItemChildModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function itemInfo(): HasOne
    {
        return $this->hasOne(VarItemInfo::class, 'id', 'item_information_id');
    }
}