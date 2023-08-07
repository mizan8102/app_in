<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgramMenuResource extends JsonResource
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
            'program_master_id' => $this->program_master_id, 
            'menu_id' => $this->menu_id,
            'menu_qty' => $this->menu_qty, 
            'menu_rate' => $this->menu_rate, 
            'menu_amount' => $this->menu_amount, 
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at, 
            'created_by' => $this->created_by, 
            'updated_by' => $this->updated_by

        ];
    }
}
