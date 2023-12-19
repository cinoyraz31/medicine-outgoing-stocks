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
        Schema::create('medicine_batch_calculate_results', function (Blueprint $table) {
            $table->id();
            $table->integer('clinic_id');
            $table->integer('medicine_id');
            $table->integer('stock');
            $table->string('batch_no')->unique();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_batch_calculate_results');
    }
};
