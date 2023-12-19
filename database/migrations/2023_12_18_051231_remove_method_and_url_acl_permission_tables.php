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
        Schema::table('acl_permissions', function($table) {
            $table->dropColumn('method');
            $table->dropColumn('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acl_permissions', function($table) {
            $table->string('method');
            $table->string('url');
        });
    }
};
