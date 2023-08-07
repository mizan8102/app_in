<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VATRegistrationType extends Model
{
    use HasFactory;

    public $table = "5a_sv_vat_registration_type";

    protected $primaryKey = 'vat_reg_id'; 
}
