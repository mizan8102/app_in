<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SvUOM extends Model
{
    use HasFactory;

    public $table = "5m_sv_uom";

    protected $primaryKey = 'id';
    protected $guarded=[];

    public function var_item_info(){
        return $this->belongsTo(SvUOM::class,'uom_id','id');
    }
}