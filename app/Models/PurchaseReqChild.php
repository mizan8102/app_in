<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReqChild extends Model
{
    use HasFactory;
    protected $table='trns00d_purchase_req_child';
    protected $fillable=[
        'purchase_req_child_id', 'requisition_number', 'purchase_req_master_id', 'item_info_id', 'required_date','indent_child_id',
        'uom_id', 'uom_short_code', 'relative_factor', 'rate', 'req_quantity', 'indent_quantity','pre_req_quantify', 
        'Remarks', 'Remarks_bn', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    public function var_item_info(){
        return $this->hasMany(VarItemInfo::class,'id','item_info_id');
    }

   public function supplier_mapping(){
    return $this->hasMany(SupplierMapping::class,'item_id','item_info_id');
   }
}
