<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSubGroup extends Model
{
    use HasFactory;

    public $table = "var_item_sub_group";

    protected $primaryKey = 'id';
}