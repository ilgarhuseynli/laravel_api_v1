<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'surname' => $this->surname,
            'name' => $this->name,
            'role' => [
                'value' => $this->role->id,
                'label' => $this->role->title,
            ],
            'phone' => $this->phone,
            'address' => $this->address,
            'email' => $this->email,
            'created_at' => strtotime($this->created_at),
        ];
    }

//    public function with($request)
//    {
//        return [
//            "success" => true,
//            "message" => "Scan info retrieved successfully."
//        ];
//    }
}
