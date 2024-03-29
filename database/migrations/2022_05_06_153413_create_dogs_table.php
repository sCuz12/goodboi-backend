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
        Schema::create('dogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('shelter_id')->nullable();
            $table->enum('listing_type', ['adopt', 'lost', 'found']);
            $table->string('title');
            $table->string('slug');
            $table->string('name');
            $table->text('description');
            $table->string('cover_image');
            $table->date('dob')->nullable();
            $table->string('size', 1);
            $table->integer('breed_id')->nullable();
            $table->integer('city_id');
            $table->integer('status_id');
            $table->integer('total_views')->default(0);
            $table->enum('gender', ['m', 'f'])->nullable();
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
        Schema::dropIfExists('dogs');
    }
};
