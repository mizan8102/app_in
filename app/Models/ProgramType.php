<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramType extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = '5z2_program_type';
    protected $primary_key = 'id';
}