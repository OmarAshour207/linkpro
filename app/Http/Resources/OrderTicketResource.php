<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderTicketResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'notes'     => $this->notes,
            'status'    => $this->status,
            'reason'    => $this->reason,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'company'   => $this->company,
            'floor'     => $this->floor,
            'path'      => $this->path,
            'office'    => $this->office,
            'content'   => $this->content
        ];
    }
}
