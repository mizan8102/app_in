<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IOCItemDetail extends Model
{
    use HasFactory;

    public $table = "tran01b_ioc_item_details";

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function item_info(){
        return $this->belongsTo(VarItemInfo::class, 'item_information_id', 'id');
    }

    public function uoms(){
        return $this->hasMany(SvUOM::class, 'uom_set_id', 'uom_set_id');
    }
}
