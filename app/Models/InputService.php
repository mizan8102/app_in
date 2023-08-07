<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputService extends Model
{
    use HasFactory;

    public $table = "5t_sv_input_service";

    protected $primaryKey = 'input_service_id'; 
}
