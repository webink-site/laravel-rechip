<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryWork extends Model
{
    protected $fillable = [
        'title',
        'content',
        'date',
        'power_points',
        'tuning_profit',
        'gallery',
        'catalog_id', // Связь с автомобилем
    ];

    protected $casts = [
        'gallery' => 'array',
    ];

    public function catalog(): BelongsTo
    {
        return $this->belongsTo(Catalog::class);
    }
}
