<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiscalYearInfo extends Model
{
    use HasFactory;
    protected $table="5x1_fiscal_year_info";
    protected $primaryKey="id";
    protected $guarded=[];
}
