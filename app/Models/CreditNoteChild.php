<?php

namespace App\Models;

use App\Models\CreditNoteMaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreditNoteChild extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'trns05b_credit_note_child';
    protected $primary_key = 'id';
    /**
     * Get the user that owns the CreditNoteChild
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cn(): BelongsTo
    {
        return $this->belongsTo(CreditNoteMaster::class, 'credit_note_id', 'id');
    }
}