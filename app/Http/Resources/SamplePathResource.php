<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SamplePathResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'title' => $this->title
        ];
    }
}
