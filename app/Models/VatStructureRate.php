<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatStructureRate extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='var_vat_structure_rates';
    protected $guarded=[];
}
