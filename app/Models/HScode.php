<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HScode extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='var_hs_code';
    protected $guarded=[];

    // public function vatStructure(){
    //     return $this->hasMany(VatStructureRate::class,'')
    // }
}
