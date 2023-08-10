<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitMetaTable extends Migration
{
    public function up()
    {
        Schema::create('unit_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->string('meta_key');
            $table->text('meta_value');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('unit_meta');
    }
}