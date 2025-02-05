<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained();
            $table->string('city')->comment('Город');
            $table->string('address')->comment('Адрес');
            $table->string('coordinates')->comment('Координаты (широта, долгота)');
            $table->string('yandex_map_link')->comment('Ссылка на Яндекс.Карты');
            $table->string('phone_number')->comment('Номер телефона');
            $table->string('work_time')->comment('Время работы');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};
