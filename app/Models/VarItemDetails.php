<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VarItemDetails extends Model
{
    use HasFactory;
    protected $table = "var_item_details";
    protected $primaryKey = 'id';
    protected $fillable = ['item_info_id', 'description', 'description_bn', 'item_image', 
    'cost', 'price', 'available_status', 'effective_date', 'created_at', 'created_by', 'updated_at', 'updated_by'];
}
