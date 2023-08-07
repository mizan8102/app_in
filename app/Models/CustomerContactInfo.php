<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerContactInfo extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'cs_customer_contact_info';
    protected $primary_key = 'id';
}
