<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Удаляем существующее поле legal_info, если оно есть
            $table->dropColumn('legal_info');

            // Добавляем необходимые поля
            $table->string('organization_name')->default('ИП Кубашичев Тимур Нурбиевич');
            $table->string('inn')->default('502988216808');
            $table->string('ogrnip')->default('324508100060659');
            $table->string('legal_address')->default('Московская область, город Мытищи');
        });
    }

    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Отменяем изменения
            $table->json('legal_info')->nullable();

            $table->dropColumn(['organization_name', 'inn', 'ogrnip', 'legal_address']);
        });
    }
};
