<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndentChildren extends Model
{
    use HasFactory;
    protected $table="trns00c_indent_ChildIndent";
    protected $fillable=[
      'indent_master_id',
      'indent_child_id' ,
      'Item_info_id' ,
      'uom_id' ,
      'req_qty',
      'indent_qty' ,
      'created_at',
      'updated_at',
      'created_by' ,
      'updated_by',
    ];
}
