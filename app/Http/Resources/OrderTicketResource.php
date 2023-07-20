<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderTicketResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'type'      => $this->type,
            'notes'     => $this->notes,
            'status'    => $this->status,
            'reason'    => $this->reason,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'company'   => new SampleCompanyResource($this->company),
            'floor'   => new SampleFloorResource($this->floor),
            'path'    => new SamplePathResource($this->path),
            'office'  => new SampleOfficeResource($this->office),
            'ticket_data'   => TicketDataTicketResource::collection($this->ticketData),
            'comments'      => CommentResource::collection($this->comments)
        ];
    }
}
