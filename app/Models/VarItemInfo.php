<?php

namespace App\Models;

use App\Models\PurchaseReqQty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VarItemInfo extends Model
{
    use HasFactory;

    public $table = "var_item_info";

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function purchase_order_child()
    {
        return $this->belongsTo(PurchaseOrderChild::class, 'item_information_id', 'item_information_id');
    }

    public function priceDeclarationItem(){
        return $this->hasMany(IOCItemDetail::class,'ioc_price_declaration_id','ioc_ref_id');
    }
    public function trns_uom(){
        return $this->hasOne(SvUOM::class,'id','trns_unit_id');
    }

    public function sv_uom()
    {
        return $this->hasOne(SvUOM::class, 'id', 'uom_id');
    }
    public function sub_group()
    {
        return $this->belongsTo(SubGroup::class, 'itm_sub_grp_id', 'id');
    }

    public function issue_child()
    {
        return $this->belongsTo(IssueChild::class, 'item_information_id', 'item_information_id');
    }

    public function receive_child()
    {
        return $this->belongsTo(ReceiveChild::class, 'item_information_id', 'item_information_id');
    }

    public function item_detail()
    {
        return $this->hasOne(VarItemDetails::class, 'item_information_id', 'id');
    }
    public function itemPurchaseOrder()
    {
        return $this->hasMany(PurchaseReqQty::class, 'item_information_id', 'id');
    }

    // realation with hs code 
    public function hsCode(){
        return $this->hasOne(HScode::class,'hs_code_id','id');
    }
}