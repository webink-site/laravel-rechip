<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'city',
        'address',
        'coordinates',
        'yandex_map_link',
        'phone_number',
        'work_time',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}