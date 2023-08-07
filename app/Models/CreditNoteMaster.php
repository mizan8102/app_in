<?php

namespace App\Models;

use App\Models\IssueMaster;
use App\Models\CreditNoteChild;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreditNoteMaster extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'trns05a_credit_note_master';
    protected $primary_key = 'id';
    /**
     * Get all of the comments for the CreditNoteMaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cnc(): HasMany
    {
        return $this->hasMany(CreditNoteChild::class, 'credit_note_id', 'id');
    }
    /**
     * Get the user that owns the CreditNoteMaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function issueMaster(): BelongsTo
    {
        return $this->belongsTo(IssueMaster::class, 'issue_master_id', 'id');
    }
}
