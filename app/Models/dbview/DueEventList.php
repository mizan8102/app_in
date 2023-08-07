<?php

namespace App\Models\dbview;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DueEventList extends Model
{
    use HasFactory;
    protected $table="DueEventPaymentList";
    protected $guarded=[];
}
