<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Engine extends Model
{
    use HasFactory;

    protected $table = 'engine';

    protected $fillable = ['slug', 'volume', 'power'];

    public function catalogs()
    {
        return $this->hasMany(Catalog::class);
    }
}