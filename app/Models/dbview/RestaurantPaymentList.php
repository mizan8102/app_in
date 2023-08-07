<?php

namespace App\Models\dbview;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantPaymentList extends Model
{
    use HasFactory;
    protected $table="RestaturantPaymentList";
    protected $guarded=[];
}
