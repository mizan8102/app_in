<?php

namespace App\Models;

use App\Models\RecvChild;
use App\Models\TransactionSourceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RecvMaster extends Model
{
    use HasFactory;
    protected $primary_key = "id";
    protected $table = "trns02a_recv_master";
    protected $guarded = [];
    /**
     * Get the user that owns the RecvMaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tranType(): BelongsTo
    {
        return $this->belongsTo(TransactionSourceType::class, 'tran_type_id', 'id');
    }
    /**
     * Get all of the comments for the RecvMaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recvChild(): HasMany
    {
        return $this->hasMany(RecvChild::class, 'receive_master_id', 'id');
    }

    public function itemStock(): HasOne{
        return $this->hasOne(ItemStockMaster::class,'receive_Issue_master_id','id');
    }
    /**
     * Get the user that owns the RecvMaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(CsCompanyStoreLocation::class, 'store_id', 'id');
    }
    /**
     * Get the user that owns the RecvMaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(SupplierDetail::class, 'supplier_id', 'id');
    }
}
