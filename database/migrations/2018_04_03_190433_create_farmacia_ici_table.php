<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFarmaciaIciTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmacia_ici', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('farmacia_id')->unsigned();
            $table->integer('ici_id')->unsigned();
            $table->integer('establecimiento_id');
            $table->integer('medicamento_cerrado')->default(1);
            $table->integer('dispositivo_cerrado')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('establecimiento_id')->references('id')->on('establecimientos');
            $table->foreign('farmacia_id')->references('id')->on('farmacias');
            $table->foreign('ici_id')->references('id')->on('icis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmacia_ici');
    }
}