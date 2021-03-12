<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MensajesMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id();
            $table->string('texto');
            $table->integer('leido');
            $table->integer('usuarioEnvia_id')->unsigned();
            $table->integer('usuarioReacibe_id')->unsigned();
            $table->integer('tarea_id')->unsigned();
            $table->foreign('usuarioRealiza_id')->references('usuario_id')->on('usuarios');
            $table->foreign('usuarioReacibe_id')->references('usuario_id')->on('usuarios');
            $table->foreign('tarea_id')->references('id')->on('actividadesRealizadas');
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
        Schema::dropIfExists('mensajes');
    }
}
