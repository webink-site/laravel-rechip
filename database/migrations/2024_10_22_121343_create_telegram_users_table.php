<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('telegram_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unique();
            $table->string('username', 50);
            $table->string('first_name', 50);
            $table->string('last_name', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('telegram_users');
    }
};
