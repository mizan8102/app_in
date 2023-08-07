<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveRawMaterialChild extends Model
{
    use HasFactory;
    protected $primary_key='Receive_child_id';
    protected $table="trns02b_recv_child";
    protected $fillable=[
        'Receive_child_id', 'receive_master_id', 'item_information_id', 'uom_id', 'uom_short_code', 'relative_factor', 
        'vat_payment_method_id', 'item_cat_for_retail_id', 'po_quantity', 'po_rate', 'rec_quantity', 'rec_qty_adjt', 'itm_receive_rate', 
        'item_value_tran_curr', 'item_value_local_curr', 'vat_rate_type_id', 'is_fixed_rate', 'cd_percent', 'cd_amount', 'rd_percent', 
        'rd_amount', 'sd_percent', 'sd_amount', 'vat_percent', 'fixed_rate_uom_id', 'fixed_rate', 'vat_amount', 'at_percent', 'at_amount', 
        'total_amount_local_curr', 'supplier_vat_percent', 'addtional_vat_percent', 'accessable_value', 'created_at', 'updated_at', 'created_by', 
        'updated_by'
    ];
}
