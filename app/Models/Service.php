<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'post_title',
        'page_content',
        'image',
        'image_wide',
        'minimal_prices',
        // Добавьте остальные поля по необходимости
    ];

    protected $casts = [
        'minimal_prices' => 'array', // Приведение к массиву
    ];

    /**
     * Получить SEO-настройки для сервиса.
     */
    public function seoSettings()
    {
        return $this->hasMany(ServiceSeoSetting::class);
    }

    // Остальные методы и отношения модели
}