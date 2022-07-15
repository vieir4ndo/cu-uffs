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
        UserType::insert([
            'id' => 1,
            'description' => 'Servidor'
        ], [
            'id' => 2,
            'description' => 'Estudante'
        ], [
            'id' => 3,
            'description' => 'Servidor'
        ], [
            'id' => 4,
            'description' => 'Servidor Terceirizado'
        ], [
                'id' => 5,
                'description' => 'Servidor Terceirizado'
            ]
        );
    }
}