<?php

namespace App\Models;

use App\Models\CsCompanyStoreLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CsUsersStores extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $primary_key = 'id';
    protected $table = 'cs_users_stores';
    /**
     * Get the user that owns the CsUsersStores
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    /**
     * Get the user that owns the CsUsersStores
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(CsCompanyStoreLocation::class, 'store_id', 'id');
    }
}
