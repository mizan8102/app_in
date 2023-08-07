<?php

namespace App\Http\Resources\ProductTree;

use Illuminate\Http\Resources\Json\JsonResource;

class MasterGroupResource extends JsonResource
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
            "key" => $this->id,
            "title" => $this->itm_mstr_grp_name
        ];
    }
}
