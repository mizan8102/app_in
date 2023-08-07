<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PProgramCard extends Model
{
    use HasFactory;
    protected $table='p_program_card';

    protected $fillable=[
        'program_master_id', 'card_id', 'use_status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

}
