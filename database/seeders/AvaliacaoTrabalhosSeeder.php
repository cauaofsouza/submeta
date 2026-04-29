<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AvaliacaoTrabalhosSeeder extends Seeder
{

/**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $avaliadores = DB::table('avaliadors')
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        $trabalhos = DB::table('trabalhos')
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        $campos = DB::table('campo_avaliacaos')
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        // função para pegar ids das listas (em "ciclo")
        $pick = function ($list, $i) {
            return $list[$i % count($list)];
        };

        DB::table('avaliacao_trabalhos')->insert([
            [
                'avaliador_id' => $pick($avaliadores, 0),
                'trabalho_id' => $pick($trabalhos, 0),
                'campo_avaliacao_id' => $pick($campos, 0),
                'nota' => 9,
            ],
            [
                'avaliador_id' => $pick($avaliadores, 1),
                'trabalho_id' => $pick($trabalhos, 1),
                'campo_avaliacao_id' => $pick($campos, 0),
                'nota' => 7,
            ],
            [
                'avaliador_id' => $pick($avaliadores, 2),
                'trabalho_id' => $pick($trabalhos, 2),
                'campo_avaliacao_id' => $pick($campos, 1),
                'nota' => 8,
            ],
        ]);
    }
}