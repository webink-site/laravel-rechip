<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('catalog_optional_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalog_id')->constrained('catalog');
            $table->foreignId('service_id')->constrained('services');
            $table->decimal('main_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->timestamps();

            $table->unique(['catalog_id', 'service_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('catalog_optional_services');
    }
};
