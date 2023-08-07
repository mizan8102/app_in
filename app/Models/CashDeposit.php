<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashDeposit extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'trns51a_cash_deposit';
    protected $primary_key = 'id';
    /**
     * Get the user that owns the CashDeposit
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function depositor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
