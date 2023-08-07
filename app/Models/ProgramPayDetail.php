<?php

namespace App\Models;

use App\Models\OrderMaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramPayDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    /**
     * Get the user that owns the ProgramPayDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderMaster(): BelongsTo
    {
        return $this->belongsTo(OrderMaster::class, 'p_program_master_id', 'id');
    }
}
