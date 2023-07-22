<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SampleTicketResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'type'      => $this->type,
            'notes'     => $this->notes,
            'status'    => $this->status,
            'reason'    => $this->reason,
            'prepare_time' => $this->prepare_time,
            'created_at' => $this->created_at
        ];
    }
}
