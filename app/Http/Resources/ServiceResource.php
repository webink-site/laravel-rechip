<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'post_title'        => $this->post_title,
            'page_content'      => $this->page_content,
            'name'              => $this->name,
            'description'       => $this->description,
            'short_description' => $this->short_description,
            'slug'              => $this->slug,
            'image'             => $this->image,
            'image_wide'        => $this->image_wide,
            'minimal_prices'    => $this->minimal_prices,
            'seo_settings'      => ServiceSeoSettingResource::collection($this->seoSettings),
        ];
    }
}