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
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('logement_type');
            $table->string('trading_type');
            $table->date('dateStart')->nullable();
            $table->date('dateEnd')->nullable();
            $table->integer('bed')->nullable();
            $table->integer('rooms');
            $table->integer('visitors')->nullable();
            $table->integer('bathroom')->nullable();
            $table->integer('price');
            $table->integer('views_nm')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
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
        Schema::dropIfExists('offers');
    }
};
