<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PurchaseReqMaster extends Model
{
    use HasFactory;
    protected $table="trns00c_purchase_req_master";
    protected $fillable=[
        'indent_master_id', 'requisition_number','is_partial','master_group_id','is_active',
         'prod_type_id', 'company_id', 'branch_id', 'store_id', 'requisition_date','itm_mstr_grp_name','phone',
         'submitted_by', 'recommended_by', 'approved_by', 'approved_status', 'remarks',
         'remarks_bn', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
    public function type(): HasOne
    {
        return $this->hasOne(ProductType::class, 'id','prod_type_id' );
    }
    public function  category():HasOne{
        return $this->hasOne(ProductCatagory::class,'id','prod_cat_id');
    }
 
    /**
     * Get all of the comments for the PurchaseReqMaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseReqChild(): HasMany
    {
        return $this->hasMany(PurchaseReqChild::class, 'purchase_req_master_id', 'id');
    }

    public function masterGroup():HasOne
    {
        return $this->hasOne(ItemMasterGroup::class,'id','master_group_id');
    }
    
    public function getMasterGroupAttribute()
    {
        return ucfirst($this->masterGroup->itm_mstr_grp_name);
    }
}