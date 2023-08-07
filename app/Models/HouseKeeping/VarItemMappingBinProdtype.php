<?php

namespace App\Models\HouseKeeping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VarItemMappingBinProdtype extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table="var_item_mapping_bin_prodtype";
    protected $guarded=[];
    }
