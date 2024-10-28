<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSeoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'level',
        'title',
        'description',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}