<?php

namespace Modules\Common\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'status' => $this->status,
            'type' => $this->type,
            'created_at' => strtotime($this->created_at),
        ];
    }

}
