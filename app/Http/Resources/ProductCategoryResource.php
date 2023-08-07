<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductTree\ProductTypeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
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
            'key'              => $this->id,
            'title'            => $this->prod_cat_name,
            'children'    => ProductTypeResource::collection($this->productTypes),
        ];
    }
}
