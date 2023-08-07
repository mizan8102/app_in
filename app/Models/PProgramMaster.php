<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PProgramMaster extends Model
{
    use HasFactory;
    protected $table='trns00g_order_master';
    protected $guarded=[];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'id'
            ]
        ];
    }

    public function pProgramMenu()
    {
        return $this->hasMany(PProgramMaster::class);
    }
}
