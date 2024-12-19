<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('catalog_seo_settings', function (Blueprint $table) {
            $table->foreign(['catalog_id'])->references(['id'])->on('services')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catalog_seo_settings', function (Blueprint $table) {
            $table->dropForeign('catalog_seo_settings_service_id_foreign');
        });
    }
};
