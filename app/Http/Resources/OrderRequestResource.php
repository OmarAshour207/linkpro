<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'description' => $this->description,
            'date'      => $this->date,
            'start_time'=> $this->start_time,
            'end_time'  => $this->end_time,
            'notes'     => $this->notes,
            'status'    => $this->status,
            'reason'    => $this->reason,
            'service'   => new ServiceResource($this->service),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
