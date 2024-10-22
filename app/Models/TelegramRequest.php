<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_data',
        'contact',
        'product',
        'region_code',
        'status',
    ];
}
