<?php

namespace App\Models;

use App\Models\RecvMaster;
use App\Models\VarItemInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecvChild extends Model
{
    use HasFactory;
    protected $primary_key="id";
    protected $table="trns02b_recv_child";
    protected $guarded=[];
    /**
     * Get the user that owns the RecvChild
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recvMaster(): BelongsTo
    {
        return $this->belongsTo(RecvMaster::class, 'receive_master_id', 'id');
    }
    /**
     * Get all of the comments for the RecvChild
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function itemInfo(): HasOne
    {
        return $this->hasOne(VarItemInfo::class, 'id', 'item_information_id');
    }
}
