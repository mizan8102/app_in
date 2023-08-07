<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsEmployeeModel extends Model
{
    use HasFactory;
    protected $table="cs_employee_master";
    protected $guarded=[];
}