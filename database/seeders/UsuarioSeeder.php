<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([

            // =====================
            // ADMIN
            // =====================
            [
                'id' => 1,
                'name' => '[ADMIN]',
                'email' => 'admin@ufrpe.br',
                'password' => Hash::make('10203040'),
                'tipo' => 'administrador',
                'email_verified_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // =====================
            // COORDENADORES
            // =====================
            [
                'id' => 2,
                'name' => '[COORDENADOR][1]',
                'email' => 'coord1@ufrpe.br',
                'password' => Hash::make('10203040'),
                'tipo' => 'coordenador',
                'email_verified_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => '[COORDENADOR][2]',
                'email' => 'coord2@ufrpe.br',
                'password' => Hash::make('10203040'),
                'tipo' => 'coordenador',
                'email_verified_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // =====================
            // PROPONENTES
            // =====================
            [
                'id' => 4,
                'name' => '[PROPONENTE][1]',
                'email' => 'prop1@ufrpe.br',
                'password' => Hash::make('10203040'),
                'tipo' => 'proponente',
                'email_verified_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => '[PROPONENTE][2]',
                'email' => 'prop2@ufrpe.br',
                'password' => Hash::make('10203040'),
                'tipo' => 'proponente',
                'email_verified_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => '[PROPONENTE][3]',
                'email' => 'prop3@ufrpe.br',
                'password' => Hash::make('10203040'),
                'tipo' => 'proponente',
                'email_verified_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // =====================
            // PARTICIPANTES
            // =====================
            [
                'id' => 7,
                'name' => '[PARTICIPANTE][1]',
                'email' => 'part1@ufrpe.br',
                'password' => Hash::make('10203040'),
                'tipo' => 'participante',
                'email_verified_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'name' => '[PARTICIPANTE][2]',
                'email' => 'part2@ufrpe.br',
                'password' => Hash::make('10203040'),
                'tipo' => 'participante',
                'email_verified_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'name' => '[PARTICIPANTE][3]',
                'email' => 'part3@ufrpe.br',
                'password' => Hash::make('10203040'),
                'tipo' => 'participante',
                'email_verified_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // =====================
            // AVALIADORES
            // =====================
            [
                'id' => 10,
                'name' => '[AVALIADOR][1]',
                'email' => 'aval1@ufrpe.br',
                'password' => Hash::make('10203040'),
                'tipo' => 'avaliador',
                'email_verified_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 11,
                'name' => '[AVALIADOR][2]',
                'email' => 'aval2@ufrpe.br',
                'password' => Hash::make('10203040'),
                'tipo' => 'avaliador',
                'email_verified_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // =====================
            // ADMIN RESPONSÁVEL
            // =====================
            [
                'id' => 12,
                'name' => '[ADMIN_RESPONSAVEL]',
                'email' => 'adminresp@ufrpe.br',
                'password' => Hash::make('10203040'),
                'tipo' => 'administradorResponsavel',
                'email_verified_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::statement("SELECT setval('users_id_seq', (SELECT MAX(id) FROM users))");
    }
}