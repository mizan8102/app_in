<?php

namespace App\Models;

use App\Models\RecvMaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionSourceType extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table='5c_sv_tran_source_type';
    protected $primary_key="id";
    /**
     * Get all of the comments for the TransactionSourceType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recvMaster(): HasMany
    {
        return $this->hasMany(RecvMaster::class, 'tran_type_id', 'id');
    }
}