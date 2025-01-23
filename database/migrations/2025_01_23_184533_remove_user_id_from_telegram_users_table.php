<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Запустить миграцию.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telegram_users', function (Blueprint $table) {
            // Удаление внешнего ключа, если он существует
            $table->dropForeign('fk_telegram_users_user');

            // Удаление столбца 'user_id'
            $table->dropColumn('user_id');
        });
    }

    /**
     * Откатить миграцию.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telegram_users', function (Blueprint $table) {
            // Восстановление столбца 'user_id'
            $table->unsignedBigInteger('user_id')->after('id'); // Убедитесь, что расположение столбца соответствует вашим требованиям

            // Восстановление внешнего ключа
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};
