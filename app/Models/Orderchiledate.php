<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderchiledate extends Model
{
    use HasFactory;
    public $table = "trns00h1_order_child_date";

    protected $primaryKey = 'id';

    protected $guarded = [];
    
}
