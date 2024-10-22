<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['title', 'slug', 'content'];

    // Приводим поле content к массиву для работы с JSON
    protected $casts = [
        'content' => 'array',
    ];
}
