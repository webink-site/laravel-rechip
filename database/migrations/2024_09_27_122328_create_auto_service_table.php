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
        Schema::create('auto_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auto_id')->constrained()->onDelete('cascade'); // Связь с авто
            $table->foreignId('service_id')->constrained()->onDelete('cascade'); // Связь с основной услугой
            $table->decimal('price', 10, 2); // Цена услуги для конкретного авто
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
        Schema::dropIfExists('auto_service');
    }
};
