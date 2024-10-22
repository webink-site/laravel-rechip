<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('region_code'); // Код региона, например "spb"
            $table->string('region_name'); // Название региона, например "Санкт-Петербург"
            $table->string('address'); // Адрес
            $table->string('phone_number'); // Номер телефона
            $table->string('work_time'); // Время работы
            $table->string('coordinates'); // Координаты
            $table->json('social_links')->nullable(); // Социальные ссылки
            $table->json('legal_info')->nullable(); // Юридическая информация
            $table->string('url')->nullable(); // URL для региона
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};
