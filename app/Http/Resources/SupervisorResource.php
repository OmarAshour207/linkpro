<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupervisorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'phonenumber'   => $this->phonenumber,
            'image'         => $this->userImage,
            'role'          => $this->role,
            'create_at'     => $this->created_at,
            'company'       => new SampleCompanyResource($this->company)
        ];
    }
}
