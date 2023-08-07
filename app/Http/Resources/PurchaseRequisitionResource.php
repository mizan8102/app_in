<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseRequisitionResource extends JsonResource
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
            'id' => $this->id,
            'requisition_number' => $this->requisition_number,
            'purchase_req_master_id' => $this->purchase_req_master_id,
            'submitted_by' => $this->submitted_by,
            'emp_name' => $this->emp_name,
            'approved_by' => $this->approved_by,
            'requisition_date' => $this->requisition_date,
            'approved_status' => $this->approved_status,
            'remarks' => $this->remarks,
            'submitted_by_name' => $this->customer_name,
            'approved_by' => $this->approved_by
        ];
    }
}
