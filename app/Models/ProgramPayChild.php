<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramPayChild extends Model
{
    use HasFactory;
    protected $fillable=[
        'program_pay_detail_id','paid','due','pay_method','pay_ref','date'
    ];
}
