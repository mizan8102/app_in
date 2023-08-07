<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ItemMasterModel extends Model
{
    use HasFactory;
    protected $table='trns00a_indent_master';
    protected $primaryKey="id";
    protected $fillable=[
        'order_master_id',
        'prod_type_id',
        'pro_req_close',
        'prod_cat_id',
        'master_group_id',
        'indent_master_id',
        'indent_number',
        'indent_date',
         'product_req',
         'company_id',
         'branch_id',
         'demand_store_id',
         'to_store_id',
         'remarks',
         'remarks_bn',
         'submitted_by',
         'issue_status',
         'close_status',
          'recommended_by',
           'approved_by',
           'approved_status',
           'created_at',
           'updated_at',
           'created_by',
           'updated_by'
    ];
    public function item_indent_child(){
        return $this->hasMany(ItemChildModel::class,'indent_master_id','id');
    }
    /**
     * Get the user that owns the ItemMasterModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'prod_type_id', 'id');
    }
    public function  category():HasOne{
        return $this->hasOne(ProductCatagory::class,'id','prod_cat_id');
    }
    public  function  masterGroup():HasOne{
        return $this->hasOne(ItemMasterGroup::class,'id','master_group_id');
    }
    /**
     * Get all of the comments for the ItemMasterModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function programs(): BelongsTo
    {
        return $this->belongsTo(OrderMaster::class, 'program_master_id', 'id');
    }


}
