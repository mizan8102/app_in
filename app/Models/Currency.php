<?php

namespace App\Models;

use App\Models\CurrencyExchangeDetails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table='5n_sv_currency_info';
    /**
     * Get all of the comments for the Currency
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exchangeRate(): HasMany
    {
        return $this->hasMany(CurrencyExchangeDetails::class, 'currency_info_id', 'id');
    }
}