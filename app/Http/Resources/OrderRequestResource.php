<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'type'      => $this->type,
            'date'      => $this->date,
            'start_time'=> $this->start_time,
            'end_time'  => $this->end_time,
            'notes'     => $this->notes,
            'status'    => $this->status,
            'reason'    => $this->reason,
            'prepare_time' => $this->prepare_time,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
            'service'   => new ServiceResource($this->service),
            'comments'  => CommentResource::collection($this->comments)
        ];
    }
}
