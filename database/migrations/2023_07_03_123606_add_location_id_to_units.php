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
         Schema::table('units', function (Blueprint $table) {
           $table->foreignId('brand_id')->nullable()->constrained('brands');
            $table->foreignId('location_id')->nullable()->constrained('locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            	$table->dropForeign(['brand_id']);
            	$table->dropColumn('brand_id');
                $table->dropForeign(['location_id']);
          		$table->dropColumn('location_id');
        });
    }
};
