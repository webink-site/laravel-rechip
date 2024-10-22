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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('service_name'); // Название услуги
            $table->string('short_description')->nullable(); // Краткое описание
            $table->text('description')->nullable(); // Полное описание
            $table->string('post_title')->nullable(); // Заголовок
            $table->text('page_content')->nullable(); // Содержание страницы
            $table->string('image')->nullable(); // Изображение
            $table->string('image_wide')->nullable(); // Широкое изображение
            $table->json('minimal_prices')->nullable(); // Минимальные цены (в формате JSON)
            $table->timestamps(); // Для создания временных меток
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }

};
