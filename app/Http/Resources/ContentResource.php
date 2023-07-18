<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'content'    => $this->content,
            'note'       => $this->note,
            'created_at' => $this->created_at
        ];
    }
}
