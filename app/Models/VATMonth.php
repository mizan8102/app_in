<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VATMonth extends Model
{
    use HasFactory;
    protected $table="5x2_vat_month_info";
    protected $guarded=[];
    protected $primaryKey="id";
}
