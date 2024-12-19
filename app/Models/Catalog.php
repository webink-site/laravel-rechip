<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    use HasFactory;

    // Указываем правильное название таблицы
    protected $table = 'catalog';

    protected $fillable = [
        'brand_id',
        'model_id',
        'configuration_id',
        'engine_id',
        'slug',
        'service_id',
        'service_main_price',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'model_id');
    }

    public function configuration()
    {
        return $this->belongsTo(Configuration::class);
    }

    public function engine()
    {
        return $this->belongsTo(Engine::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function chipTuningParam()
    {
        return $this->hasOne(ChipTuningParam::class);
    }

    public function catalogOptionalServices()
    {
        return $this->hasMany(CatalogOptionalService::class);
    }
}