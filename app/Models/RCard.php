<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RCard extends Model
{
    use HasFactory;
    protected $table='r_card';
    protected $primaryKey="id";
    protected $fillable=[
        'card_category_id',
          'card_type_id',
          'card_number',
          'card_number_bn',
          'is_free',
          'is_active',
          'created_at',
          'updated_at',
          'created_by',
          'updated_by'
    ];
}
