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
        Schema::table('gallery_works', function (Blueprint $table) {
            $table->foreign(['catalog_id'])->references(['id'])->on('catalog')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gallery_works', function (Blueprint $table) {
            $table->dropForeign('gallery_works_catalog_id_foreign');
        });
    }
};
