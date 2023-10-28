<?php 

namespace App\DTO;
use Illuminate\Support\Facades\Auth;

class IssueMasterDTO
{
    public $indent_master_id;
    public $receive_master_id;
    public $tran_source_type_id;
    public $tran_type_id;
    public $tran_sub_type_id;
    public $prod_type_id;
    public $company_id;
    public $branch_id;
    public $store_id;
    public $currency_id;
    public $excg_rate;
    public $customer_id;
    public $emi_master_id;
    public $reg_status;
    public $customer_bin_number;
    public $customer_bin_number_bn;
    public $bank_branch_id;
    public $bank_account_type_id;
    public $customer_account_number;
    public $is_reg_bank_trans;
    public $delivery_to;
    public $delivery_to_bn;
    public $fiscal_year_id;
    public $vat_month_id;
    public $custom_office_id;
    public $issue_number;
    public $issue_number_bn;
    public $issue_date;
    public $employee_id;
    public $department_id;
    public $requisition_num;
    public $requisition_num_bn;
    public $sales_invoice_date;
    public $is_vds_applicable;
    public $delivery_purpose;
    public $delivery_date;
    public $port_discharge;
    public $vehicle_num;
    public $vehicle_num_bn;
    public $vehicle_type;
    public $total_issue_amount_before_discount;
    public $total_issue_amt_local_curr_before_discount;
    public $total_discount;
    public $total_cd_amount;
    public $total_rd_amount;
    public $total_sd_amnt;
    public $total_vat_amnt;
    public $total_issue_amount;
    public $total_issue_amt_local_curr;
    public $challan_type;
    public $challan_number;
    public $challan_number_bn;
    public $challan_date;
    public $remarks;
    public $remarks_bn;
    public $monthly_proc_status;
    public $yearly_proc_status;
    public $print_status;
    public $created_by;
    public $updated_by;

    public function __construct($data,$issue_number=null, $issueAmt)
    {
        $this->indent_master_id     = $data['indent_id'] ?? $data['indent_master_id'];
        $this->receive_master_id    = $data['receive_master_id'] ?? null;
        $this->tran_source_type_id  = $data['tran_source_type_id'] ?? 2;
        $this->tran_type_id         = $data['tran_type_id'] ?? 10;
        $this->tran_sub_type_id     = $data['tran_sub_type_id'] ?? null;
        $this->prod_type_id         = $data['prod_type_id'] ?? null ;
        $this->company_id           = $data['company_id'] ?? Auth::user()->company_id;
        $this->branch_id            = $data['branch_id']  ?? Auth::user()->branch_id;
        $this->store_id             = $data['store_id'] ?? Auth::user()->store_id;
        $this->currency_id          = $data['currency_id'] ?? 1;
        $this->excg_rate            = $data['excg_rate'] ?? 1;
        $this->customer_id          = $data['customer_id'] ?? null;
        $this->emi_master_id        = $data['emi_master_id'] ?? null;
        $this->reg_status           = $data['reg_status'] ?? null;
        $this->customer_bin_number  = $data['customer_bin_number'] ?? null;
        $this->customer_bin_number_bn = $data['customer_bin_number_bn'] ?? null;
        $this->bank_branch_id       = $data['bank_branch_id'] ?? null;
        $this->bank_account_type_id = $data['bank_account_type_id'] ?? null;
        $this->customer_account_number = $data['customer_account_number'] ?? null;
        $this->is_reg_bank_trans    = $data['is_reg_bank_trans'] ?? null;
        $this->delivery_to          = $data['delivery_to'] ?? null;
        $this->delivery_to_bn       = $data['delivery_to_bn'] ?? null;
        $this->fiscal_year_id       = $data['fiscal_year_id'] ?? 1;
        $this->vat_month_id         = $data['vat_month_id'] ?? 1;
        $this->custom_office_id     = $data['custom_office_id'] ?? null;
        $this->issue_number         = $data['issue_number'] ?? $issue_number;
        $this->issue_number_bn      = $data['issue_number_bn'] ?? $issue_number;
        $this->issue_date           = $data['issue_date'] ?? $data['transferDate'] ;
        $this->employee_id          = $data['employee_id'] ?? null;
        $this->department_id        = $data['department_id'] ?? null;
        $this->requisition_num      = $data['requisition_num'] ?? null;
        $this->requisition_num_bn   = $data['requisition_num_bn'] ?? null;
        $this->sales_invoice_date   = $data['sales_invoice_date'] ?? null;
        $this->is_vds_applicable    = $data['is_vds_applicable'] ?? null;
        $this->delivery_purpose     = $data['delivery_purpose'] ?? null;
        $this->delivery_date        = $data['delivery_date'] ?? $data['transferDate'];
        $this->port_discharge       = $data['port_discharge'] ?? null;
        $this->vehicle_num          = $data['vehicle_num'] ?? null;
        $this->vehicle_num_bn       = $data['vehicle_num_bn'] ?? null;
        $this->vehicle_type         = $data['vehicle_type'] ?? null;
        $this->total_issue_amount_before_discount = $data['total_issue_amount_before_discount'] ?? $issueAmt;
        $this->total_issue_amt_local_curr_before_discount = $data['total_issue_amt_local_curr_before_discount'] ?? $issueAmt;
        $this->total_discount       = $data['total_discount'] ?? 0;
        $this->total_cd_amount      = $data['total_cd_amount'] ?? 0;
        $this->total_rd_amount      = $data['total_rd_amount'] ?? 0;
        $this->total_sd_amnt        = $data['total_sd_amnt'] ?? 0;
        $this->total_vat_amnt       = $data['total_vat_amnt'] ?? 0;
        $this->total_issue_amount   = $data['total_issue_amount'] ?? $issueAmt;
        $this->total_issue_amt_local_curr = $data['total_issue_amt_local_curr'] ?? $issueAmt;
        $this->challan_type         = $data['challan_type'] ?? null;
        $this->challan_number       = $data['challan_number'] ?? $data['challan_no'];
        $this->challan_number_bn    = $data['challan_number_bn'] ?? $data['challan_no'];
        $this->challan_date         = date('Y-m-d H:i:s',strtotime($data['challan_date'])) ?? date('Y-m-d H:i:s');
        $this->remarks              = $data['remarks'];
        $this->remarks_bn           = $data['remarks_bn'];
        $this->monthly_proc_status  = $data['monthly_proc_status'];
        $this->yearly_proc_status   = $data['yearly_proc_status'];
        $this->print_status         = $data['print_status'];
        $this->created_by           = $data['created_by'] ?? Auth::user()->id;
        $this->updated_by           = $data['updated_by'] ?? Auth::user()->id;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}
