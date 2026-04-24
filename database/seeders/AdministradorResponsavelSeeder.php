<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdministradorResponsavelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = DB::table('users')->where('tipo','administradorResponsavel')->pluck('id');

        foreach ($users as $id) {
            DB::table('administrador_responsavels')->insert([
                'user_id' => $id,
            ]);
        }
    }
}
