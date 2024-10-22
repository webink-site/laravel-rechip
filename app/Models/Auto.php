<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auto extends Model
{
    use HasFactory;

    protected $fillable = [
        'auto_full_name',
        'brand',
        'model',
        'generation',
        'configuration',
        'modification',
        'carbase_modification_id',
        'stages_increase_params',
        'mark_id',
        'model_id',
        'generation_id',
    ];

    protected $casts = [
        'stages_increase_params' => 'array', // Приводим к массиву, чтобы Laravel мог корректно обрабатывать JSON
    ];

    /**
     * Основные услуги, связанные с авто
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'auto_service')->withPivot('price')->withTimestamps();
    }

    /**
     * Дополнительные услуги, связанные с авто
     */
    public function additionalServices()
    {
        return $this->belongsToMany(AdditionalService::class, 'auto_additional_service')->withPivot('price')->withTimestamps();
    }
}
