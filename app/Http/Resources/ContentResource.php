<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'note'       => $this->note,
            'office'     => new OfficeResource($this->office),
            'path'       => new PathResource($this->path),
            'floor'      => new FloorResource($this->floor),
            'created_at' => $this->created_at
        ];
    }
}
