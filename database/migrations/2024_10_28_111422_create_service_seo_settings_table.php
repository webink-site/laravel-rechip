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
        Schema::create('service_seo_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete(); // Связь с услугой
            $table->string('level'); // Уровень (Марка, Модель, Поколение, Модификация)
            $table->string('title')->nullable(); // SEO Title
            $table->text('description')->nullable(); // SEO Description
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
        Schema::dropIfExists('service_seo_settings');
    }
};
