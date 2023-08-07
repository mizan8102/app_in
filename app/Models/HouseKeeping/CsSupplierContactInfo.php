<?php

namespace App\Models\HouseKeeping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsSupplierContactInfo extends Model
{
    use HasFactory;
    protected $table="cs_supplier_contact_info";
    protected $primaryKey="id";
    protected $guarded=[];
    
}
