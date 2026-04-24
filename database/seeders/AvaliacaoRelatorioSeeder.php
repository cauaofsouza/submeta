<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AvaliacaoRelatorioSeeder extends Seeder
{
    public function run()
    {
        $users = DB::table('users')
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        $arquivos = DB::table('arquivos')
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        if (count($users) === 0 || count($arquivos) === 0) {
            return;
        }

        $pick = function ($list, $i) {
            return $list[$i % count($list)];
        };

        DB::table('avaliacao_relatorios')->insert([
            [
                'user_id' => $pick($users, 0),
                'arquivo_id' => $pick($arquivos, 0),
                'tipo' => 'Parcial',
            ],
            [
                'user_id' => $pick($users, 1),
                'arquivo_id' => $pick($arquivos, 1),
                'tipo' => 'Final',
            ],
        ]);
    }
}