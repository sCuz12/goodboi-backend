<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shelters', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('shelter_name')->nullable();
            $table->string('slug')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_profile_complete')->default(0);
            $table->integer('city_id')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('facebook_pagename')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shelters');
    }
};
