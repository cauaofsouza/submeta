<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoordenadorComissaoSeeder extends Seeder
{
    public function run()
    {
        $users = DB::table('users')
            ->where('tipo', 'coordenador')
            ->pluck('id');

        foreach ($users as $id) {
            DB::table('coordenador_comissaos')->insert([
                'user_id' => $id,
            ]);
        }
    }
}