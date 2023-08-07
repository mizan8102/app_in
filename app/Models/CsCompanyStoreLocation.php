<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CsCompanyStoreLocation extends Model
{
    use HasFactory;
    protected $table = 'cs_company_store_location';
    protected $primary_key = 'id';
    protected $fillable = [
        'store_id', 'branch_id', 'sl_name', 'sl_name_bn', 'sl_address', 'sl_address_bn', 'sl_officer_id', 'sl_type', 'is_default_location', 'is_sales_point', 'is_virtual_location', 'is_active', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
    /**
     * Get all of the comments for the CsCompanyStoreLocation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'store_id', 'id');
    }
}
