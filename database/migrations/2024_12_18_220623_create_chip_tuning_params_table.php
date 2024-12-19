<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chip_tuning_params', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalog_id')->unique()->constrained('catalog')->onDelete('cascade');
            $table->decimal('torque', 10, 2)->nullable();
            $table->decimal('stage1_power_value', 10, 2)->nullable();
            $table->decimal('stage1_torque_value', 10, 2)->nullable();
            $table->decimal('stage1_price', 10, 2)->nullable();
            $table->decimal('stage2_power_value', 10, 2)->nullable();
            $table->decimal('stage2_torque_value', 10, 2)->nullable();
            $table->decimal('stage2_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chip_tuning_params');
    }
};
