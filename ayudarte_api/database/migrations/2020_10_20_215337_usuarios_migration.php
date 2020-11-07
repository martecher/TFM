<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsuariosMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('usuario_id');
            $table->string('nombre');
            $table->string('apellido1');
            $table->string('apellido2');
            $table->date('fechaNacimiento');
            $table->boolean('exento');
            $table->float('bolsaHora');
            $table->float('reputacion');
            $table->boolean('administrador');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('usuarios');
    }
}
