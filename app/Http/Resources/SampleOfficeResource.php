<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SampleOfficeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'title' => $this->title
        ];
    }
}
