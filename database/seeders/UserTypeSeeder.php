<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserType::truncate();

        UserType::insert([[
            'id' => 1,
            'description' => 'Servidor',
        ], [
            'id' => 2,
            'description' => 'Estudante',
        ], [
            'id' => 3,
            'description' => 'Servidor Restaurante Universitário',
        ], [
            'id' => 4,
            'description' => 'Servidor Terceirizado Restaurante Universitário',
        ], [
            'id' => 5,
<<<<<<< Updated upstream
            'description' => 'Servidor Terceirizado Restaurante Universitário',
        ]]
        );
=======
            'description' => 'Servidor de Cadastro de Salas',
        ],
        [
            'id' => 6,
            'description' => 'Usuário de Cadastro de Salas',
        ], [
            'id' => 7,
            'description' => 'Locador de Salas',
        ], [
            'id' => 8,
            'description' => 'Administrador do Cadastro de Salas',
        ]
        ]);
>>>>>>> Stashed changes
    }
}
