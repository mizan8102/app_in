<?php

namespace App\Models;

use App\Models\CsCompanyStoreLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Floor extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'var_restaurant_floor';
    protected $primary_key = 'id';
    /**
     * Get the user that owns the Floor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(CsCompanyStoreLocation::class, 'r_restaurant_id', 'id');
    }
}
