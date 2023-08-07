<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
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
            'store_id' => $this->store_id,
            'floor_id' => $this->floor_id,
            'customer_id' => $this->customer_id,
            'program_type_id' => $this->program_type_id,
            'program_name' => $this->program_name,
            'program_name_bn' => $this->program_name_bn,
            'prog_date' => $this->prog_date,
            'start_time' => $this->start_time,
            'session_name' => $this->session_name,
            'end_time' => $this->end_time,
            // 'company_id' => 'required',
            'status' => $this->status,
            // 'due_amount' => $this->due_amount,
            'prog_start_time' => $this->prog_start_time,
            'customer_name' => $this->customer_name,
            'phone_number' => $this->phone_number,
            'floor_name' => $this->floor_name,
            'prog_end_time' => $this->prog_end_time,
            'number_of_guest' => $this->number_of_guest,
            'hall_room_charge' => $this->hall_room_charge,
            'hall_room_vat' => $this->hall_room_vat,
            'food_charge' => $this->food_charge,
            'total_amount' => $this->total_amount,
            'vat_amount' => $this->vat_amount,
            'total_amount_with_vat' => $this->total_amount_with_vat,
            'due_amount' => $this->due_amount,
            'is_active' => $this->is_active,
            'is_print' => $this->is_print,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'paid_amount' => $this->paid_amount,
            // 'program_childs' => ProgramMenuResource::collection($this->sales)
           
        ];
    }
}
