<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('engine', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique();
            $table->string('volume', 255)->nullable();
            $table->integer('power');
            $table->unique(['volume', 'power']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('engine');
    }
};
