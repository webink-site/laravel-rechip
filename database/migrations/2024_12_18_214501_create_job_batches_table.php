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
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->nullable();
            $table->string('name')->nullable();
            $table->integer('total_jobs')->nullable();
            $table->integer('pending_jobs')->nullable();
            $table->integer('failed_jobs')->nullable();
            $table->text('failed_job_ids')->nullable();
            $table->text('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at')->nullable();
            $table->integer('finished_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_batches');
    }
};
