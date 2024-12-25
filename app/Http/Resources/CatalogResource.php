<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CatalogResource extends JsonResource
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
            'slug' => $this->slug,
            'service' => [
                'name' => $this->service->name,
                'slug' => $this->service->slug,
            ],
            'brand' => [
                'name' => $this->brand->name,
                'slug' => $this->brand->slug,
                'catalog_image' => $this->brand->catalog_image,
            ],
            'model' => [
                'name' => $this->carModel->name,
                'slug' => $this->carModel->slug,
                'catalog_image' => $this->carModel->catalog_image,
            ],
            'configuration' => [
                'name' => $this->configuration->name,
                'slug' => $this->configuration->slug,
            ],
            'engine' => [
                'slug' => $this->engine->slug,
                'volume' => $this->engine->volume,
                'power' => $this->engine->power,
            ],
            'service_main_price' => $this->service_main_price,
            'chip_tuning_param' => $this->chipTuningParam, // Можно также создать ресурс для этого, если нужно
            'optional_services' => OptionalServiceResource::collection($this->catalogOptionalServices),
        ];
    }
}