<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketDataTicketResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'note'      => $this->note,
            'content'   => new ContentResource($this->content)
        ];
    }
}
