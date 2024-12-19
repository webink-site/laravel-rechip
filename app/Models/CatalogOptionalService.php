<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogOptionalService extends Model
{
    use HasFactory;

    protected $fillable = [
        'catalog_id',
        'service_id',
        'main_price',
        'sale_price',
    ];

    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}