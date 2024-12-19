<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            // Добавьте только те столбцы, которых нет в таблице
            if (!Schema::hasColumn('services', 'short_description')) {
                $table->string('short_description')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('services', 'post_title')) {
                $table->string('post_title')->nullable()->after('description');
            }
            if (!Schema::hasColumn('services', 'page_content')) {
                $table->text('page_content')->nullable()->after('post_title');
            }
            if (!Schema::hasColumn('services', 'image_wide')) {
                $table->string('image_wide')->nullable()->after('image');
            }
            if (!Schema::hasColumn('services', 'minimal_prices')) {
                $table->json('minimal_prices')->nullable()->after('image_wide');
            }
        });
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            // Удалите только те столбцы, которые были добавлены
            $table->dropColumn([
                'short_description',
                'post_title',
                'page_content',
                'image_wide',
                'minimal_prices',
            ]);
        });
    }
};
