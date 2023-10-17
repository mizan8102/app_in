<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransferMaster extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'trns06a_transfer_master';
    protected $guarded = [];
    /**
     * Get all of the comments for the TransferMaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function toutChild(): HasMany
    {
        return $this->hasMany(ToutProductReqQuantity::class, 'issue_master_id', 'local_key');
    }

    public function productreq(){
        return $this->hasOne(ItemMasterModel::class,'id','indent_master_id');
    }
}
