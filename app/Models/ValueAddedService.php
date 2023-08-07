<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValueAddedService extends Model
{
    use HasFactory;

    public $table = "5r_sv_vas";

    protected $primaryKey = 'vas_id'; 
}
