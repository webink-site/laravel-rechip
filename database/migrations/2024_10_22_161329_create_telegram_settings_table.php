<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('telegram_settings', function (Blueprint $table) {
            $table->id();
            $table->text('bot_start_message')->nullable();
            $table->text('bot_stop_message')->nullable();
            $table->timestamps();
        });

        // Инициализация с дефолтными значениями
        DB::table('telegram_settings')->insert([
            'bot_start_message' => 'Добро пожаловать! Как мы можем помочь вам?',
            'bot_stop_message' => 'Вы отписались от уведомлений.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_settings');
    }
};
