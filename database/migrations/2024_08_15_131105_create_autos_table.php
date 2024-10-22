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
        Schema::create('autos', function (Blueprint $table) {
            $table->id();
            $table->string('auto_full_name'); // Полное наименование авто
            $table->string('brand'); // Марка авто
            $table->string('model'); // Модель авто
            $table->string('generation'); // Поколение авто
            $table->string('configuration'); // Конфигурация авто
            $table->string('modification'); // Модификация авто
            $table->unsignedBigInteger('carbase_modification_id'); // ID модификации CarBase.ru
            $table->json('stages_increase_params')->nullable()->after('carbase_modification_id'); // Добавляем поле для показателей прироста
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
        Schema::dropIfExists('autos');
    }
};
