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
            $table->increments('id');
            $table->string('texto');
            $table->integer('leido');
            $table->integer('orden');
            $table->integer('usuarioEnvia_id')->unsigned();
            $table->integer('usuarioRecibe_id')->unsigned();
            $table->integer('tarea_id')->unsigned();

            $table->foreign('usuarioEnvia_id')->references('usuario_id')->on('usuarios');
            $table->foreign('usuarioRecibe_id')->references('usuario_id')->on('usuarios');
            //$table->foreign('tarea_id')->references('id')->on('actividadesrealizadas');
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
