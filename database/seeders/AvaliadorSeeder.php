<?php

use Illuminate\Database\Seeder;

class AvaliadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = DB::table('users')->where('tipo','avaliador')->pluck('id');

        foreach ($users as $index => $id) {
            DB::table('avaliadors')->insert([
                'user_id' => $id,
                'area_id' => 1,//ai e foda
                'tipo' => $index % 2 == 0 ? 'Externo' : 'Interno',
            ]);
        }
    }
}
