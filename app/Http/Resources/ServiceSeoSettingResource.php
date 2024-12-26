<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceSeoSettingResource extends JsonResource
{
    /**
     * Преобразовать ресурс в массив.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'level'       => $this->level,
            'title'       => $this->title,
            'description' => $this->description,
        ];
    }
}