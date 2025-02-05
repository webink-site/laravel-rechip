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
        // Удаляем 'legal_info' и добавляем новые поля
        'organization_name',
        'inn',
        'ogrnip',
        'legal_address',
        'url',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
