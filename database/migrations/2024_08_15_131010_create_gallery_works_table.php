<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_works', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Название работы
            $table->text('content'); // Описание работы
            $table->timestamp('date'); // Дата работы
            $table->string('power_points'); // Мощность (л.с.)
            $table->string('tuning_profit'); // Прибыль от тюнинга (%)
            $table->json('gallery')->nullable(); // Галерея изображений (массив URL)
            $table->foreignId('auto_id')->constrained()->onDelete('cascade'); // Связь с автомобилем (модель Auto)
            $table->timestamps(); // Время создания и изменения записи
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gallery_works');
    }
};
