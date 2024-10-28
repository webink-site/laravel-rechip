<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
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
     * Автомобили, связанные с услугой
     */
    public function autos()
    {
        return $this->belongsToMany(Auto::class, 'auto_service')->withPivot('price')->withTimestamps();
    }

    /**
     * SEO-настройки услуги.
     */
    public function seoSettings()
    {
        return $this->hasMany(ServiceSeoSetting::class);
    }
}
