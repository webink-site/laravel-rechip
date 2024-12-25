<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    // Указываем, если таблица называется 'models' или другое
    protected $table = 'models';

    protected $fillable = ['name', 'slug', 'catalog_image'];

    public function catalogs()
    {
        return $this->hasMany(Catalog::class, 'model_id');
    }
}