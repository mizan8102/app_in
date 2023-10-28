<?php

namespace App\DTO;

class IssueChildDTO
{
  public $id;
  public $issue_master_id;
  public $card_id;
  public $item_info_id;
  public $uom_id;
  public $uom_short_code;
  public $relative_factor;
  public $vat_payment_method_id;
  public $item_cat_for_retail_id;
  public $issue_qty;
  public $issue_qty_adjt;
  public $discount_percent;
  public $item_rate;
  public $issue_rate;
  public $discount;
  public $mrp_value;
  public $item_value_tran_curr;
  public $item_value_local_curr;
  public $vat_rate_type_id;
  public $is_fixed_rate;
  public $cd_percent;
  public $cd_amount;
  public $rd_percent;
  public $rd_amount;
  public $indent_child_id;
  public $sd_percent;
  public $sd_amount;
  public $vat_percent;
  public $fixed_rate_uom_id;
  public $fixed_rate;
  public $vat_amount;
  public $vds_percent;
  public $total_amount_local_curr;
  public $trn_unit_id;
  public $inventory_method_id;
  public $itm_trade_rate;
  public $itm_wholesle_rate;
  public $itm_export_rate;
  public $created_at;
  public $updated_at;
  public $created_by;
  public $updated_by;
  public $itm_export_currency_id;

  public function __construct($data, $issueMasterId)
  {
    $this->id = $data['id'];
    $this->issue_master_id = $data['issue_master_id'] ?? $issueMasterId;
    $this->card_id = $data['card_id'];
    $this->item_info_id = $data['item_info_id'];
    $this->uom_id = $data['uom_id'];
    $this->uom_short_code = $data['uom_short_code'];
    $this->relative_factor = $data['relative_factor'];
    $this->vat_payment_method_id = $data['vat_payment_method_id'];
    $this->item_cat_for_retail_id = $data['item_cat_for_retail_id'];
    $this->issue_qty = $data['issue_qty'];
    $this->issue_qty_adjt = $data['issue_qty_adjt'];
    $this->discount_percent = $data['discount_percent'];
    $this->item_rate = $data['item_rate'];
    $this->issue_rate = $data['issue_rate'];
    $this->discount = $data['discount'];
    $this->mrp_value = $data['mrp_value'];
    $this->item_value_tran_curr = $data['item_value_tran_curr'];
    $this->item_value_local_curr = $data['item_value_local_curr'];
    $this->vat_rate_type_id = $data['vat_rate_type_id'];
    $this->is_fixed_rate = $data['is_fixed_rate'];
    $this->cd_percent = $data['cd_percent'];
    $this->cd_amount = $data['cd_amount'];
    $this->rd_percent = $data['rd_percent'];
    $this->rd_amount = $data['rd_amount'];
    $this->indent_child_id = $data['indent_child_id'];
    $this->sd_percent = $data['sd_percent'];
    $this->sd_amount = $data['sd_amount'];
    $this->vat_percent = $data['vat_percent'];
    $this->fixed_rate_uom_id = $data['fixed_rate_uom_id'];
    $this->fixed_rate = $data['fixed_rate'];
    $this->vat_amount = $data['vat_amount'];
    $this->vds_percent = $data['vds_percent'];
    $this->total_amount_local_curr = $data['total_amount_local_curr'];
    $this->trn_unit_id = $data['trn_unit_id'];
    $this->inventory_method_id = $data['inventory_method_id'];
    $this->itm_trade_rate = $data['itm_trade_rate'];
    $this->itm_wholesle_rate = $data['itm_wholesle_rate'];
    $this->itm_export_rate = $data['itm_export_rate'];
    $this->created_at = $data['created_at'];
    $this->updated_at = $data['updated_at'];
    $this->created_by = $data['created_by'];
    $this->updated_by = $data['updated_by'];
    $this->itm_export_currency_id = $data['itm_export_currency_id'];
  }

  public function toArray()
  {
    return get_object_vars($this);
  }

  public function toJson()
  {
    return json_encode($this->toArray());
  }
}
