<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('telegram_requests', function (Blueprint $table) {
            $table->id();
            $table->text('request_data');
            $table->string('contact');
            $table->string('product')->nullable();
            $table->string('region_code');
            $table->string('status')->default('new');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('telegram_requests');
    }
};
