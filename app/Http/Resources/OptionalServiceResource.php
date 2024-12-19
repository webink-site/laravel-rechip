<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionalServiceResource extends JsonResource
{
    /**
     * Преобразовать ресурс в массив.
     *
     * @param Request $request
     * @return array
     */

    public function toArray(Request $request): array
    {
        return [
            'path' => $this->catalog->slug,
            'service' => [
                "slug" => $this->service->slug,
                "name" => $this->service->name,
                "short_description" => $this->service->short_description,
                "image" => $this->service->image
            ]
        ];
    }
}