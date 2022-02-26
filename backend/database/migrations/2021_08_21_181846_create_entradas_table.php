<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntradasTable extends Migration
{
    public function up()
    {
        Schema::create('entradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuarios')->onDelete('cascade');
            $table->string('id_entrada')->nullable();

            $table->dateTime('inicio');
            $table->dateTime('fim')->nullable();
            $table->string('motivo')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('entradas');
    }
}
