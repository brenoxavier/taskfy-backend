<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('id_clockify')->unique();

            $table->string('nome');
            $table->string('email')->unique();
            $table->string('senha');
            $table->string('foto_perfil');
            $table->integer('carga_horaria')->default(8);
            $table->integer('banco_horas')->default(0);
            $table->boolean('sabado')->default(false);
            $table->boolean('ativo')->default(false);
            $table->boolean('admin')->default(false);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
