<?php

namespace App\Models;

use App\Models\CustomerContactInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerDetails extends Model
{
    use HasFactory;
    public $table = "cs_customer_details";
    protected $primaryKey = 'id';
    protected $guarded = [];
    /**
     * Get the user associated with the CustomerDetails
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function contact(): HasOne
    {
        return $this->hasOne(CustomerContactInfo::class, 'customer_id', 'id');
    }
}
