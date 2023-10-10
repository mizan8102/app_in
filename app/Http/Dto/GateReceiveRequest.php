<?php 

namespace App\Http\Dto;

use Auth;
class GateReceiveReceiveRequest 
{
  
  public string $name;
  public string $email;
  public string $password;

  public function __construct(array $data)
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

  public function rules()
  {
      return [
          'issue_master' => 'nullable|string|max:255',
          'purchase_order_date' => 'nullable|date',
          'tran_source_type_id' => 'nullable|integer',
          'prod_type_id' => 'nullable|string|max:255',
          'vat_rebate_id' => 'nullable|integer',
          'supplier_id' => 'nullable|integer',
          'supplier_bin_number' => 'nullable|string|max:255',
          'bank_branch_id' => 'nullable|integer',
          'bank_account_type_id' => 'nullable|integer',
          'is_reg_bank_trans' => 'nullable|integer',
          'supplier_account_number' => 'nullable|string|max:255',
          'receive_date' => 'nullable|date',
          'fiscal_year_id' => 'nullable|integer',
          'vat_month_id' => 'nullable|integer',
          'grn_number' => 'nullable|string|max:255',
          'grn_number_bn' => 'nullable|string|max:255',
          'grn_date' => 'nullable|date',
          'port_discharge' => 'nullable|string|max:255',
          'chalan_type' => 'nullable|integer',
          'chalan_number' => 'nullable|integer',
          'chalan_number_bn' => 'nullable|integer',
          'chalan_date' => 'nullable|date',
          'total_cd_amount' => 'nullable|numeric',
          'total_rd_amount' => 'nullable|numeric',
          'total_sd_amount' => 'nullable|numeric',
          'total_vat_amount' => 'nullable|numeric',
          'total_at_amount' => 'nullable|numeric',
          'total_exp_amount' => 'nullable|numeric',
          'total_assamble_amount' => 'nullable|numeric',
          'total_receive_amount' => 'nullable|numeric',
          'total_recv_amt_local_curr' => 'nullable|numeric',
          'inspection_number' => 'nullable|string|max:255',
          'inspection_number_bn' => 'nullable|string|max:255',
          'monthly_proc_status' => 'nullable|integer',
          'yearly_proc_status' => 'nullable|integer',
          'is_vds_done' => 'nullable|integer',
          'is_tariff' => 'nullable|integer',
          'trariff_val' => 'nullable|numeric',
          'trariff_percent' => 'nullable|numeric',
          'import_duty_head' => 'nullable|string|max:255',
          'duty_percent' => 'nullable|numeric',
          'duty_free_amount' => 'nullable|numeric',
          'duty_currency_id' => 'nullable|integer',
          'remarks' => 'nullable|string|max:255',
          'remarks_bn' => 'nullable|string|max:255',
          'created_by' => 'nullable|integer',
      ];
  }
  
}