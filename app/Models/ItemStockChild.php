<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemStockChild extends Model
{
    use HasFactory;
    protected $table="trns_itemstock_child";
    protected $primary_key="itemstock_child_id";
    protected $guarded=[];
    
}