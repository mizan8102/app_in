<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableType extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'var_table_type';
    protected $primary_key = 'id';
}
