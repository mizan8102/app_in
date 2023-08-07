<?php

namespace App\Models\report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySalesSummery extends Model
{
    use HasFactory;
    protected $table="DailySalesSummary";
    protected $guarded=[];
}
