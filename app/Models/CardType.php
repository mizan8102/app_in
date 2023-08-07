<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardType extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'card_types';
    protected $primary_key = 'id';
}
