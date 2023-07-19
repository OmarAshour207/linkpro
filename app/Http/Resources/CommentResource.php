<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'content'   => $this->content,
            'ticket'    => new SampleTicketResource($this->ticket),
            'user'      => new SampleUserResource($this->user)
        ];
    }
}
