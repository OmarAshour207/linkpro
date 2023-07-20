<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SampleUserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'phonenumber' => $this->phonenumber,
            'role'      => $this->role,
            'image'     => $this->image,
            'created_at' => $this->created_at,
        ];
    }
}
