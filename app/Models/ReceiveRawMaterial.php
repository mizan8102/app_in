<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveRawMaterial extends Model
{
    use HasFactory;
    protected $primary_key='receive_master_id';
    protected $table="trns02a_recv_master";
    protected $fillable=[
        'receive_master_id',
        'purchase_order_master_id', 
        'purchase_order_date',
        'tran_source_type_id', 
        'tran_type_id', 
        'tran_sub_type_id', 
        'prod_type_id', 
        'vat_rebate_id', 
        'company_id', 
        'branch_id', 
        'store_id', 
        'currency_id', 
        'excg_rate', 
        'supplier_id', 
        'reg_status', 
        'supplier_bin_number', 
        'supplier_bin_number_bn', 
        'bank_branch_id', 
        'bank_account_type_id', 
        'is_reg_bank_trans', 
        'supplier_account_number', 
        'receive_date', 
        'fiscal_year', 
        'vat_month', 
        'grn_number', 
        'grn_number_bn', 
        'grn_date', 
        'port_discharge', 
        'chalan_type', 
        'chalan_number', 
        'chalan_number_bn', 
        'chalan_date', 
        'duty_chalan_number', 
        'duty_chalan_number_bn', 
        'duty_chalan_date', 
        'total_cd_amount', 
        'total_rd_amount', 
        'total_sd_amount', 
        'total_vat_amount', 
        'total_at_amount', 
        'total_exp_amount', 
        'total_assamble_amount', 
        'total_receive_amount', 
        'total_recv_amt_local_curr', 
        'inspection_number', 
        'inspection_number_bn', 
        'remarks', 
        'remarks_bn', 
        'monthly_proc_status', 
        'yearly_proc_status', 
        'is_vds_done', 
        'is_tariff', 
        'trariff_val', 
        'trariff_percent', 
        'import_duty_head', 
        'duty_percent', 
        'duty_free_amount', 
        'duty_currency', 
        'created_at', 
        'updated_at', 
        'created_by', 
        'updated_by', 
        'cs_supplier_details_supplier_id', 
        '5c_sv_tran_source_type_tran_source_type_id'
    ];
}
