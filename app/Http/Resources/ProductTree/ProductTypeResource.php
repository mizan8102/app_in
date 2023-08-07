<?php

namespace App\Http\Resources\ProductTree;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductTypeResource extends JsonResource
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
            "title" => $this->prod_type_name,
            "children" => MasterGroupResource::collection($this->itemMasterGroups),
        ];
    }
}
