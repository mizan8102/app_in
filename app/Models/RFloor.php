<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RFloor extends Model
{
    use HasFactory;

    public $table = "r_floor";

    protected $fillable = [ 
        'r_restaurant_id', 
        'floor_name', 
        'floor_name_bn', 
        'is_active',   
        'created_by',
        'updated_by'
    ];
}
