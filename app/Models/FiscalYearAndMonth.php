<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiscalYearAndMonth extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table='5x1_fiscal_year_info';
    protected $primary_key='id';
}