<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketDataSupplyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'supply'    => new SupplyResource($this->supply),
            'quantity'  => $this->quantity,
            'unit'      => $this->unit,
            'note'      => $this->note
        ];
    }
}
