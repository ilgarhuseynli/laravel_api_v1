<?php

namespace App\Http\Resources\Admin;

use App\Classes\File;
use App\Classes\Role;
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
            'avatar' => File::getFileObject($this->avatar,'user'),
            'role' => Role::getById($this->role_id),
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
