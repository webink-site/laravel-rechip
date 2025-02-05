<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'region_code',
        'region_name',
        'social_links',
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
