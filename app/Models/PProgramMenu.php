<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PProgramMenu extends Model
{
    use HasFactory;

    protected $table='p_program_menu';
    protected $fillable=[
        'program_master_id', 'menu_id','menu_qty', 'menu_rate', 'menu_amount', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
    public function pProgramMaster()
    {
        return $this->hasMany(PProgramMaster::class);
    }
}
