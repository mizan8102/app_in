<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsseIndentMaster extends Model
{
    use HasFactory;
    protected $primary_key='issue_master_id';
    protected $table='trns03a_issue_master';
    protected $fillable=[
        'indent_master_id',
        'tran_source_type_id' ,
        'tran_type_id',
        'tran_sub_type_id' ,
        'prod_type_id' ,
        'company_id' ,
        'branch_id' ,
        'store_id' ,
        'currency_id' ,
        'excg_rate' ,
        'customer_id',
        'reg_status' ,
        'customer_bin_number' ,
        'customer_bin_number_bn' ,
        'bank_branch_id' ,
        'bank_account_type_id' ,
        'customer_account_number' ,
        'is_reg_bank_trans' ,
        'delivery_to' ,
        'delivery_to_bn' ,
        'fiscal_year' ,
        'vat_month' ,
        'custom_office' ,
        'issue_number' ,
        'issue_number_bn',
        'issue_date' ,
        'employee_id' ,
        'department_id' ,
        'requisition_num' ,
        'requisition_num_bn' ,
        'sales_invoice_date' ,
        'is_vds_applicable' ,
        'delivery_purpose',
        'delivery_date' ,
        'port_discharge' ,
        'vehicle_num' ,
        'vehicle_num_bn' ,
        'vehicle_type' ,
        'total_discount' ,
        'total_cd_amount' ,
        'total_rd_amount',
        'total_sd_amnt' ,
        'total_vat_amnt',
        'total_issue_amount' ,
        'total_issue_amt_local_curr',
        'challan_type',
        'challan_number' ,
        'challan_number_bn' ,
        'challan_date' ,
        'remarks' ,
        'remarks_bn' ,
        'monthly_proc_status' ,
        'yearly_proc_status',
        'print_status' ,
        'received_by',
        'created_at' ,
        'updated_at' ,
        'created_by',
        'updated_by',
        '5c_sv_tran_source_type_tran_source_type_id'
    ];
}
