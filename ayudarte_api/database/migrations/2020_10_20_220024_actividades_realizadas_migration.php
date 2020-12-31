<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ActividadesRealizadasMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividadesRealizadas', function (Blueprint $table) {
            $table->id();
            $table->string('observacion');
            $table->float('horasReales');
            $table->float('valoracion');
            $table->float('puntuacionSolicita');
            $table->integer('finalizada');
            $table->integer('usuarioSolicita_id')->unsigned();
            $table->integer('usuarioRealiza_id')->unsigned();
            $table->integer('habilidad_id')->unsigned();
            $table->foreign('usuarioRealiza_id')->references('usuario_id')->on('usuarios');
         $table->foreign('usuarioSolicita_id')->references('usuario_id')->on('usuarios');
            $table->foreign('habilidad_id')->references('habilidad_id')->on('habilidades');
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
        Schema::dropIfExists('actividades_realizadas');
    }
}
