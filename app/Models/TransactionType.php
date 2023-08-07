<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table= '5d2_sv_tran_type';
    protected $primary_key='id';
}