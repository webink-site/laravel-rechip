<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    // Связь с автомобилем
    public function auto()
    {
        return $this->belongsTo(Auto::class);
    }
}
