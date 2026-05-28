<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProgramaDeExtensaoIdToTrabalhosTable extends Migration
{
    public function up()//aqui foi adicionado o fk como nullabe pois nem todo trabalho pode pertencer ao programa, e foi adicionado o status as well
    {
        Schema::table('trabalhos', function (Blueprint $table) {

            $table->unsignedBigInteger('programa_de_extensao_id')
                ->nullable();

            $table->enum('programa_extensao_status', [
                'pendente',
                'aceito',
                'rejeitado'
            ])->nullable();

            $table->foreign('programa_de_extensao_id')
                ->references('id')
                ->on('eventos')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('trabalhos', function (Blueprint $table) {

            $table->dropForeign(['programa_de_extensao_id']);

            $table->dropColumn('programa_de_extensao_id');
        });
    }
}
