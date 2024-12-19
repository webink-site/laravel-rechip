<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'slug',
        'short_description',
        'description',
        'post_title',
        'page_content',
        'image',
        'image_wide',
        'minimal_prices',
    ];

    protected $casts = [
        'minimal_prices' => 'array',
    ];

    /**
     * Каталоги, связанные с услугой
     */
    public function catalogs()
    {
        return $this->hasMany(Catalog::class, 'service_id');
    }

    /**
     * Дополнительные услуги в каталоге
     */
    public function catalogOptionalServices()
    {
        return $this->hasMany(CatalogOptionalService::class, 'service_id');
    }

    /**
     * SEO-настройки услуги.
     */
    public function seoSettings()
    {
        return $this->hasMany(ServiceSeoSetting::class);
    }
}