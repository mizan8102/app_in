<?php 

namespace App\DTO;

use Auth;
class PurchaseRecvDTO 
{
  
 public $purchase_order_master_id;
  public $issue_master_id;
  public $purchase_order_date;
  public $tran_source_type_id;
  public $tran_type_id;
  public $tran_sub_type_id;
  public $prod_type_id;
  public $vat_rebate_id;
  public $company_id;
  public $branch_id;
  public $store_id;
  public $currency_id;
  public $excg_rate;
  public $supplier_id;
  public $reg_status;
  public $supplier_bin_number;
  public $supplier_bin_number_bn;
  public $bank_branch_id;
  public $bank_account_type_id;
  public $is_reg_bank_trans;
  public $supplier_account_number;
  public $receive_date;
  public $fiscal_year_id;
  public $vat_month_id;
  public $grn_number;
  public $grn_number_bn;
  public $grn_date;
  public $port_discharge;
  public $chalan_type;
  public $chalan_number;
  public $chalan_number_bn;
  public $chalan_date;
  public $total_cd_amount;
  public $total_rd_amount;
  public $total_sd_amount;
  public $total_vat_amount;
  public $total_at_amount;
  public $total_exp_amount;
  public $total_assamble_amount;
  public $total_receive_amount;
  public $total_recv_amt_local_curr;
  public $inspection_number;
  public $inspection_number_bn;
  public $monthly_proc_status;
  public $yearly_proc_status;
  public $is_vds_done;
  public $is_tariff;
  public $trariff_val;
  public $trariff_percent;
  public $import_duty_head;
  public $duty_percent;
  public $duty_free_amount;
  public $duty_currency_id;
  public $remarks;
  public $remarks_bn;
  public $created_by;

  public function __construct(array $data,$grnNumber = null)
  {
      $this->purchase_order_master_id = $data['poId'] ?? '';
      $this->issue_master = $data['issue_master'] ?? '';
      $this->purchase_order_date = date(strtotime($data['purchase_order_date'] ),'Y-m-d') ?? date('Y-m-d');
      $this->tran_source_type_id = $data['tran_source_type_id'] ?? 1;
      $this->tran_type_id = $data['tran_type_id'] ?? 1;
      $this->tran_sub_type_id = $data['tran_sub_type_id'] ?? 6;
      $this->prod_type_id = $data['prod_type_id'] ?? "";
      $this->vat_rebate_id = $data['vat_rebate_id'] ?? null;
      $this->company_id =  Auth::user()->company_id;
      $this->branch_id =  Auth::user()->branch_id;
      $this->store_id = Auth::user()->store_id;
      $this->currency_id = $data['currency_id'] ?? 1;
      $this->excg_rate = $data['excg_rate'] ?? 1;
      $this->supplier_id = $data['supplier_id'] ?? null;
      $this->reg_status = $data['reg_status'] ?? 0;
      $this->supplier_bin_number = $data['supplier_bin_number'] ?? null;
      $this->supplier_bin_number_bn = $data['supplier_bin_number_bn'] ?? 1;
      $this->bank_branch_id = $data['bank_branch_id'] ?? null;
      $this->bank_account_type_id = $data['bank_account_type_id'] ?? null;

      $this->is_reg_bank_trans = $data['tran_source_type_id'] ?? null;
      $this->supplier_account_number = $data['supplier_account_number'] ?? 1;
      $this->receive_date = date(strtotime($data['recv_date'] ),'Y-m-d') ?? date('Y-m-d'); 
      $this->fiscal_year_id = $data['fiscal_year_id'] ?? 1;
      $this->vat_month_id = $data['vat_month_id'] ?? 1;
      $this->grn_number = $data['grn_number'] ?? null;
      $this->grn_number_bn = $data['grn_number_bn'] ?? null;
      $this->grn_date = date(strtotime($data['grn_date'] ),'Y-m-d') ?? null; 
      $this->port_discharge = $data['port_discharge'] ?? null;
      $this->chalan_type = $data['chalan_type'] ?? 1;
      $this->chalan_number = $data['chalan_number'] ?? 1;
      $this->chalan_number_bn = $data['chalan_number_bn'] ?? 1;
      $this->chalan_date = date(strtotime($data['chalan_date'] ),'Y-m-d') ?? date('Y-m-d'); 
      
      $this->total_cd_amount = $data['total_cd_amount'] ?? 0;
      $this->total_rd_amount = $data['total_rd_amount'] ?? 0;
      $this->total_sd_amount = $data['total_sd_amount'] ?? 0;
      $this->total_vat_amount = $data['total_vat_amount'] ?? 0;
      $this->total_at_amount = $data['total_at_amount'] ?? 0;

      $this->total_exp_amount = $data['total_exp_amount'] ?? 0;
      $this->total_assamble_amount = $data['total_assamble_amount'] ?? 0;
      $this->total_receive_amount = collect($data->item_row)->sum(function ($item) {
        return $item->orderRate * $item->order_quantity;
    }) ?? 1;
      $this->total_recv_amt_local_curr	 = collect($data->item_row)->sum(function ($item) {
        return $item->orderRate * $item->order_quantity;
    }) ?? 1;
      $this->inspection_number = $data['inspection_number'] ?? null;
      $this->inspection_number_bn = $data['inspection_number_bn'] ?? null;
      $this->monthly_proc_status = $data['monthly_proc_status'] ?? 0;
      $this->yearly_proc_status = $data['yearly_proc_status'] ?? 0;
      $this->is_vds_done = $data['is_vds_done'] ?? null;
      $this->is_tariff = $data['is_tariff'] ?? null;
      $this->trariff_val = $data['trariff_val'] ?? null;
      $this->trariff_percent = $data['trariff_percent'] ?? null;
      $this->import_duty_head = $data['import_duty_head'] ?? null;
      $this->duty_percent = $data['duty_percent'] ?? null;
      $this->duty_free_amount = $data['duty_free_amount'] ?? null;
      $this->duty_currency_id = $data['duty_currency_id'] ?? null;
      $this->remarks = $data['remarks'] ?? null;
      $this->remarks_bn = $data['remarks_bn'] ?? null;
      $this->created_by = Auth::user()->id;
  }

   public function toArray()
    {
        return [
            'purchase_order_master_id' => $this->purchase_order_master_id,
            'issue_master_id' => $this->issue_master_id,
            'purchase_order_date' => $this->purchase_order_date,
            'tran_source_type_id' => $this->tran_source_type_id,
            'tran_type_id' => $this->tran_type_id,
            'tran_sub_type_id' => $this->tran_sub_type_id,
            'prod_type_id' => $this->prod_type_id,
            'vat_rebate_id' => $this->vat_rebate_id,
            'company_id' => $this->company_id,
            'branch_id' => $this->branch_id,
            'store_id' => $this->store_id,
            'currency_id' => $this->currency_id,
            'excg_rate' => $this->excg_rate,
            'supplier_id' => $this->supplier_id,
            'reg_status' => $this->reg_status,
            'supplier_bin_number' => $this->supplier_bin_number,
            'supplier_bin_number_bn' => $this->supplier_bin_number_bn,
            'bank_branch_id' => $this->bank_branch_id,
            'bank_account_type_id' => $this->bank_account_type_id,
            'is_reg_bank_trans' => $this->is_reg_bank_trans,
            'supplier_account_number' => $this->supplier_account_number,
            'receive_date' => $this->receive_date,
            'fiscal_year_id' => $this->fiscal_year_id,
            'vat_month_id' => $this->vat_month_id,
            'grn_number' => $this->grn_number,
            'grn_number_bn' => $this->grn_number_bn,
            'grn_date' => $this->grn_date,
            'port_discharge' => $this->port_discharge,
            'chalan_type' => $this->chalan_type,
            'chalan_number' => $this->chalan_number,
            'chalan_number_bn' => $this->chalan_number_bn,
            'chalan_date' => $this->chalan_date,
            'total_cd_amount' => $this->total_cd_amount,
            'total_rd_amount' => $this->total_rd_amount,
            'total_sd_amount' => $this->total_sd_amount,
            'total_vat_amount' => $this->total_vat_amount,
            'total_at_amount' => $this->total_at_amount,
            'total_exp_amount' => $this->total_exp_amount,
            'total_assamble_amount' => $this->total_assamble_amount,
            'total_receive_amount' => $this->total_receive_amount,
            'total_recv_amt_local_curr' => $this->total_recv_amt_local_curr,
            'inspection_number' => $this->inspection_number,
            'inspection_number_bn' => $this->inspection_number_bn,
            'monthly_proc_status' => $this->monthly_proc_status,
            'yearly_proc_status' => $this->yearly_proc_status,
            'is_vds_done' => $this->is_vds_done,
            'is_tariff' => $this->is_tariff,
            'trariff_val' => $this->trariff_val,
            'trariff_percent' => $this->trariff_percent,
            'import_duty_head' => $this->import_duty_head,
            'duty_percent' => $this->duty_percent,
            'duty_free_amount' => $this->duty_free_amount,
            'duty_currency_id' => $this->duty_currency_id,
            'remarks' => $this->remarks,
            'remarks_bn' => $this->remarks_bn,
            'created_by' => $this->created_by,
        ];
    }
}