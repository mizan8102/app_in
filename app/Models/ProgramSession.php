<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramSession extends Model
{
    use HasFactory;
    protected $table="var_program_sessions";
    protected $fillable=[
        'session_name', 'start_time', 'end_time'
    ];
}
