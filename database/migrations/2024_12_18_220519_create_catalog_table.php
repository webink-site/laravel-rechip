<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('catalog', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('brands');
            $table->foreignId('model_id')->constrained('car_models');
            $table->foreignId('configuration_id')->nullable()->constrained('configuration');
            $table->foreignId('engine_id')->constrained('engine');
            $table->string('slug', 255)->nullable();
            $table->foreignId('service_id')->constrained('services');
            $table->decimal('service_main_price', 10, 2)->nullable();
            $table->timestamps();

            $table->unique(['brand_id', 'model_id', 'configuration_id', 'engine_id', 'service_id'], 'catalog_unique_key');
        });
    }

    public function down()
    {
        Schema::dropIfExists('catalog');
    }
};
