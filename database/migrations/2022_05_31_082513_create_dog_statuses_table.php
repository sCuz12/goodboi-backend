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
        //acceptable statuses => (,active=2,adopted=3,deleted=3,expired=4,disabled=5)
        Schema::create('dog_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('name', ['disabled', 'active', 'adopted', 'deleted', 'expired']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dog_statuses');
    }
};
