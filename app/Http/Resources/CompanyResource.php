<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'phonenumber'   => $this->phonenumber,
            'role'          => $this->role,
            'image'         => $this->userImage,
            'address'       => $this->address,
            'lat'           => $this->lat,
            'lng'           => $this->lng,
            'created_at'    => $this->created_at,
            'supervisor'    => new SupervisorResource($this->supervisor),
            'floors'        => FloorResource::collection($this->floors),
            'supplies'      => SupplyResource::collection($this->supplies)
        ];
    }
}
