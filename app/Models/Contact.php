<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'region_code',
        'region_name',
        'address',
        'phone_number',
        'work_time',
        'coordinates',
        'social_links',
        'legal_info',
        'url',
    ];

    // Приводим поля social_links и legal_info к массиву, так как они будут храниться в формате JSON
    protected $casts = [
        'social_links' => 'array',
        'legal_info' => 'array',
    ];
}
