<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecvMasterPoChild extends Model
{
    use HasFactory;
    protected $table = "trns02b1_recv_master_po_child_qty";
    protected $primaryKey = 'id';
    protected $guarded = [];
}
