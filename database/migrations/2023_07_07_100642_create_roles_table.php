<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    

    public function down()
{
    Schema::table('users', function (Blueprint $table) {
        // Check if the foreign key constraint exists
        if (Schema::hasColumn('users', 'role_id')) {
            $table->dropForeign(['role_id']);
        }
    });

    Schema::table('users', function (Blueprint $table) {
        // Check if the column exists before dropping it
        if (Schema::hasColumn('users', 'role_id')) {
            $table->dropColumn('role_id');
        }
    });
}
};
