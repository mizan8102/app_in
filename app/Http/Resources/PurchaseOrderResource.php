<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'purchase_req_master_id' => $this->purchase_req_master_id,
            'item_information_id' => $this->item_information_id,
            'uom_id' => $this->uom_id,
            'uom_short_code' => $this->uom_short_code,
            'req_quantity' => $this->req_quantity,
            'required_date' => date('d-m-Y',strtotime($this->required_date)),
            'Remarks' => $this->Remarks,
            'pre_order_quantity' => $this->order_quantity,
            'display_itm_name' => $this->display_itm_name,
            'rate' => $this->pu_rate,
            'balance' => 0,
            'order_quantity' => $this->req_quantity,
            'isModal' => false,
            'sup_id' => $this->sup_id,
            'supplier_id' => $this->supplier_id,
            'supplier_name' => $this->supplier_name,
            'pre_order_quantity' => $this->pre_order_quantity,
            'totalRequisition' => $this->totalRequisition,
            'supplier_mapping'=> $this->supplier_mapping
        ];
    }
}
