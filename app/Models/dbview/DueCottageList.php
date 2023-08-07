<?php

namespace App\Models\dbview;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DueCottageList extends Model
{
    use HasFactory;
    protected $table = "CottagePaymentList";
    protected $guarded=[];
}
