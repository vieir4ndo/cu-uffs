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
        ],[
            'id' => 5,
            'description' => 'Administrador Cadastro de Salas',
        ]
        ]);
    }
}
