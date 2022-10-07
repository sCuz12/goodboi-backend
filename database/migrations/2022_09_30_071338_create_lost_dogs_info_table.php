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
        Schema::create('lost_dogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dog_id')->unsigned();
            $table->date('lost_at')->nullable();
            $table->integer("reward")->nullable()->default(0);
            $table->integer("location_id");
            $table->foreign('dog_id')->references('id')->on('dogs')->onDelete('cascade');
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
        Schema::dropIfExists('lost_dogs');
    }
};
