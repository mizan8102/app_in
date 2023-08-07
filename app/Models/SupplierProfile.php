<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierProfile extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'cs_supplier_details';
    protected $primary_key = 'id';
}
