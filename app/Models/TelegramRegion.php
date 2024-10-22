<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramRegion extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_code',
        'telegram_account',
    ];
}
