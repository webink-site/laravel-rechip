<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalService extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'description',
        'image',
    ];

    /**
     * Автомобили, связанные с дополнительной услугой
     */
    public function autos()
    {
        return $this->belongsToMany(Auto::class, 'auto_additional_service')->withPivot('price')->withTimestamps();
    }
}
