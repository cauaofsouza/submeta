<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProponenteSeeder extends Seeder
{
    public function run()
    {
        $users = DB::table('users')->where('tipo','proponente')->pluck('id');

        $dados = [
            [
                'titulacaoMaxima' => 'Mestrado',
                'anoTitulacao' => 2018,
                'areaFormacao' => 'Computação',
                'bolsistaProdutividade' => 'Não',
                'nivel' => '2',
            ],
            [
                'titulacaoMaxima' => 'Doutorado',
                'anoTitulacao' => 2015,
                'areaFormacao' => 'Engenharia de Software',
                'bolsistaProdutividade' => 'Sim',
                'nivel' => '1A',
            ],
            [
                'titulacaoMaxima' => 'Doutorado',
                'anoTitulacao' => 2020,
                'areaFormacao' => 'Inteligência Artificial',
                'bolsistaProdutividade' => 'Não',
                'nivel' => '1B',
            ],
        ];

        foreach ($users as $index => $user_id) {
            $base = $dados[$index % count($dados)];

            DB::table('proponentes')->insert([
                'user_id' => $user_id,
                'SIAPE' => 'SIAPE' . $user_id,
                'cargo' => 'Professor',
                'vinculo' => 'Efetivo',
                'titulacaoMaxima' => $base['titulacaoMaxima'],
                'anoTitulacao' => $base['anoTitulacao'],
                'areaFormacao' => $base['areaFormacao'],
                'bolsistaProdutividade' => $base['bolsistaProdutividade'],
                'nivel' => $base['nivel'],
                'linkLattes' => 'http://lattes.cnpq.br/' . rand(1000000000000000, 9999999999999999),
            ]);
        }
    }
}