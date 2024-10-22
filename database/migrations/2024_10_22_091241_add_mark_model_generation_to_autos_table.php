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
        Schema::table('autos', function (Blueprint $table) {
            $table->string('mark_id')->nullable()->after('carbase_modification_id');
            $table->string('model_id')->nullable()->after('mark_id');
            $table->string('generation_id')->nullable()->after('model_id');
        });
    }

    /**
     * Откат миграции.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('autos', function (Blueprint $table) {
            $table->dropColumn('mark_id');
            $table->dropColumn('model_id');
            $table->dropColumn('generation_id');
        });
    }
};
