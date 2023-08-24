<?php

namespace App\Models;

use App\Models\PProgramMenu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderMaster extends Model
{
    use HasFactory;

    public $table = "trns00g_order_master";

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function orderChild()
    {
        return $this->hasMany(OrderChild::class, 'order_master_id', 'id');
    }

    public function varItemInfo(){
        return $this->hasOne(VarItemInfo::class,'item_info_id','id');
    }

    public function order_childs_supp()
    {
        return $this->hasMany(OrderChild::class, 'order_master_id', 'id')->where('is_supplimentary', 1);
    }

    public function o_status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status', 'id');
    }
    public function hallRoom(){
        return $this->hasOne(CsCompanyStoreLocation::class,'id','floor_id');
    }


    public function indent():HasOne{
        return $this->hasOne(ItemMasterModel::class,'program_master_id','id');
    }

    /**
     * Get all of the comments for the OrderMaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function programMenu(): HasMany
    {
        return $this->hasMany(PProgramMenu::class, 'program_master_id', 'id');
    }
    /**
     * Get the user that owns the OrderMaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerDetails::class, 'customer_id', 'id');

    }

    public function paymentmaster(){
        return $this->hasMany(PaymentMaster::class,'order_id','id');
    }
}
