<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionSubType extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = '5d3_sv_tran_sub_type';
    protected $primary_key = 'id';
}