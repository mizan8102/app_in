<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CsCustomerDetails extends Model
{
    use HasFactory;
    protected  $table="cs_customer_details";
    protected  $guarded=[];
    protected $primaryKey="id";

    public function Customercontact():HasOne{
        return $this->hasOne(CustomerContactInfo::class,'customer_id','id');
    }


}
