<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user'      => new SampleUserResource($this->user),
            'activity'  => $this->activity,
            'created_at'=> $this->created_at
        ];
    }
}
