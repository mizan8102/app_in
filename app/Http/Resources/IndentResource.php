<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IndentResource extends JsonResource
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
            'indent_master_id' => $this->indent_master_id, 
            'program_master_id' => $this->program_master_id, 
            'indent_number' => $this->indent_number, 
            'prod_type_id' => $this->prod_type_id, 
            'company_id' => $this->company_id, 
            'branch_id' => $this->branch_id, 
            'store_id' => $this->store_id, 
            'indent_date' => $this->indent_date, 
            'emp_name' => $this->emp_name,
            'idd' => $this->idd,
            'remarks' => $this->remarks, 
            'remarks_bn' => $this->remarks_bn,
            'submitted_by' => $this->submitted_by, 
            'recommended_by' => $this->recommended_by, 
            'approved_by' => $this->approved_by, 
            'approved_status' => $this->approved_status, 
            'program_name' => $this->program_name,
            'created_at' => $this->created_at, 
            'updated_at' => $this->updated_at, 
            'created_by' => $this->created_by, 
            'updated_by' => $this->updated_by
        ];
    }
}
