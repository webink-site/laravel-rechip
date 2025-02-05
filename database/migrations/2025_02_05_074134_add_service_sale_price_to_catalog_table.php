<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalog', function (Blueprint $table) {
            // Добавляем новое поле 'service_sale_price' типа decimal с 8 цифрами и 2 знаками после запятой
            $table->decimal('service_sale_price', 8, 2)->nullable()->after('service_main_price');
        });
    }

    /**
     * Откат миграции.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalog', function (Blueprint $table) {
            // Удаляем поле 'service_sale_price' при откате миграции
            $table->dropColumn('service_sale_price');
        });
    }
};
