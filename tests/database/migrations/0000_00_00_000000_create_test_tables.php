<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestTables extends Migration
{
    public function up(): void
    {
        Schema::create('dummy_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('dummy_relation_model_id')->unsigned()->nullable();
            $table->timestamps();
        });
        Schema::create('dummy_relation_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dummy_models');
    }
}
