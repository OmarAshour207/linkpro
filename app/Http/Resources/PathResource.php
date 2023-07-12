<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PathResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'title' => $this->title,
            'offices'   => OfficeResource::collection($this->offices)
        ];
    }
}
