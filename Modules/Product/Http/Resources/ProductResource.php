<?php

namespace Modules\Product\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'price' => $this->price,
            'status' => $this->status,
            'position' => $this->position,
            'description' => $this->description,
            'category' => [
                'label' => $this->category->title,
                'value' => $this->category->id,
            ],

            'image' => $this->getAvatarRes(),
            'created_at' => strtotime($this->created_at),
        ];
    }

}
