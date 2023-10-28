<?php 

namespace App\DTO;
use Illuminate\Support\Facades\Auth;


class TransferOutDTO {
  
  public $indent_master_id;
  public $transfer_date;
  public $challan_number;
  public $challan_number_bn;
  public $challan_date;
  public $issuing_store_id;
  public $receiving_store_id;
  public $issue_master_id;
  public $receive_master_id;
  public $transfer_status;
  public $vehicle_no;
  public $total_item_amount;
  public $total_amount_bn;
  public $total_sd_amount;
  public $total_sd_amount_bn;
  public $total_vat_amount;
  public $total_vat_amount_bn;
  public $monthly_process_status;
  public $yearly_process_status;
  public $remarks;
  public $created_by;
  public $updated_by;

  public function __construct($data ,$issue_master_id)
  {
      $this->indent_master_id   = $data['indent_id'] ?? null;
      $this->transfer_date      = $data['transferDate'] ?? now();
      $this->challan_number     = $data['challan_no'] ?? null;
      $this->challan_number_bn  = $data['challan_number_bn'] ?? null;
      $this->challan_date       = date('Y-m-d H:i:s',strtotime($data['challan_date'])) ?? date('Y-m-d H:i:s');
      $this->issuing_store_id   = $data['issuing_store_id'] ?? $data['store_id'] ?? 2;
      $this->receiving_store_id = $data['store'] ?? 3;
      $this->issue_master_id    = $data['issue_master_id'] ?? $issue_master_id;
      $this->receive_master_id  = $data['receive_master_id'] ?? null;
      $this->transfer_status    = $data['transfer_status'] ?? 'Goods in Transit';
      $this->vehicle_no         = $data['vehicle_no'] ?? null;
      $this->total_item_amount  = $data['total_item_amount'] ?? null;
      $this->total_amount_bn    = $data['total_amount_bn'] ?? null;
      $this->total_sd_amount    = $data['total_sd_amount'] ?? null;
      $this->total_sd_amount_bn = $data['total_sd_amount_bn'] ?? null;
      $this->total_vat_amount   = $data['total_vat_amount'] ?? null;
      $this->total_vat_amount_bn    = $data['total_vat_amount_bn'] ?? null;
      $this->monthly_process_status = $data['monthly_process_status'] ?? null;
      $this->yearly_process_status  = $data['yearly_process_status'] ?? null;
      $this->remarks            = $data['remarks'] ?? null;
      $this->created_by         = $data['created_by'] ?? Auth::user()->id;
      $this->updated_by         = $data['updated_by'] ?? Auth::user()->id;
  }


   public function toArray()
    {
        return get_object_vars($this);
    }

}