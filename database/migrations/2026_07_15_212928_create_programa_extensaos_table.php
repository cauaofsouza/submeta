<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramaExtensaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programa_extensaos', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('nome');
            $table->text('descricao');

            $table->unsignedBigInteger('coordenador_id');
            $table->foreign('coordenador_id')
                ->references('id')
                ->on('coordenador_comissaos')
                ->onDelete('restrict');

            $table->unsignedBigInteger('vice_coordenador_id')->nullable();
            $table->foreign('vice_coordenador_id')
                ->references('id')
                ->on('coordenador_comissaos')
                ->onDelete('set null');

            $table->date('vigencia_inicio');
            $table->date('vigencia_fim');


            $table->string('pdf_edital')->nullable();
            $table->string('modelo_documento')->nullable();

            $table->unsignedBigInteger('criador_id');
            $table->foreign('criador_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');

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
        Schema::dropIfExists('programa_extensaos');
    }
}