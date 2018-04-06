<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFarmaciaPetitorioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmacia_petitorio', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('farmacia_id')->unsigned();
            $table->integer('petitorio_id')->unsigned();
            $table->integer('tipo_dispositivo_medico_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('farmacia_id')->references('id')->on('farmacias');
            $table->foreign('petitorio_id')->references('id')->on('petitorios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmacia_petitorio');
    }
}
