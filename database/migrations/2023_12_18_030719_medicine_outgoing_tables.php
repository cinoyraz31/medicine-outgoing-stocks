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
        Schema::create('medicine_outgoings', function (Blueprint $table) {
            $table->id();
            $table->integer('clinic_id');
            $table->integer('medicine_id');
            $table->integer('unit_id');
            $table->integer('quantity');
            $table->string('batch_no');
            $table->date('exp_date');
            $table->string('notes');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_outgoings');
    }
};
