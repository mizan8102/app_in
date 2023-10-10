<?php

namespace App\Http\Resources\Receive;

use Illuminate\Http\Resources\Json\JsonResource;

class ReceiveGateShowResource extends JsonResource
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
            "recv_master_id"    => $this->id,
            "receive_date"      => $this->receive_date,
            "master_group_name" => $this->masterGroup->itm_mstr_grp_name,
            "supplierName"      => $this->supplier->supplier_name,
            "item_row"          => $this->recvChild
        
        ];
    }
}
