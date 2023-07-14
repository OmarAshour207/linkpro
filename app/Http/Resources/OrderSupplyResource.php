<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderSupplyResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'status'    => $this->status,
            'reason'    => $this->reason,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'supplies'  => TicketDataResource::collection($this->ticketData)
        ];
    }
}
