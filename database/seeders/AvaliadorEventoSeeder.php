<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AvaliadorEventoSeeder extends Seeder
{
    public function run()
    {
        // pega avaliadores reais do banco (sem assumir IDs)
        $avaliadores = DB::table('avaliadors')
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        // segurança: evita crash se não houver avaliadores
        if (count($avaliadores) === 0) {
            return;
        }

        // pega eventos existentes
        $eventos = DB::table('eventos')
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        if (count($eventos) === 0) {
            return;
        }

        // função determinística (flat)
        $pick = function ($list, $i) {
            return $list[$i % count($list)];
        };

        DB::table('avaliador_evento')->insert([
            [
                'avaliador_id' => $pick($avaliadores, 0),
                'evento_id' => $pick($eventos, 0),
                'convite' => true,
            ],
            [
                'avaliador_id' => $pick($avaliadores, 1),
                'evento_id' => $pick($eventos, 0),
                'convite' => true,
            ],
            [
                'avaliador_id' => $pick($avaliadores, 2),
                'evento_id' => $pick($eventos, 1),
                'convite' => true,
            ],
            [
                'avaliador_id' => $pick($avaliadores, 3),
                'evento_id' => $pick($eventos, 2),
                'convite' => true,
            ],
        ]);
    }
}