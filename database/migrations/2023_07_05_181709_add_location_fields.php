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
        Schema::table('locations', function (Blueprint $table) {
            $table->string('airport_store_name')->nullable();
            $table->string('airport_code')->nullable();
            $table->string('terminal')->nullable();
            $table->string('retailer')->nullable();
            $table->string('country')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['airport_store_name', 'airport_code', 'terminal', 'retailer', 'country']);
        });
    }
}; 
