<?php

namespace App\Models\HouseKeeping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UomSet extends Model
{
    use HasFactory;
    protected $table = "5l_sv_uom_set";
    protected $primaryKey = 'id';
    protected $guarded = [];
}
