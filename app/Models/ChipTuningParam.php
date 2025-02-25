<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChipTuningParam extends Model
{
    use HasFactory;

    protected $table = 'chip_tuning_params';
    public $timestamps = false;

    protected $fillable = [
        'catalog_id',
        'torque',
        'stage1_power_value',
        'stage1_torque_value',
        'stage1_price',
        'stage2_power_value',
        'stage2_torque_value',
        'stage2_price',
    ];

    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }
}