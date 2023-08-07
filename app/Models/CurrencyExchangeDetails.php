<?php

namespace App\Models;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CurrencyExchangeDetails extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table ='5p_sv_currency_exc_rate';
    /**
     * Get the user that owns the CurrencyExchangeDetails
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_info_id', 'id');
    }
}