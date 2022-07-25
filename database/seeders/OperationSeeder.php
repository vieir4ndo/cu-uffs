<?php

namespace Database\Seeders;

use App\Models\Operation;
use Illuminate\Database\Seeder;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Operation::truncate();

        Operation::insert([[
                'id' => 1,
                'description' => 'Criação de usuário com IdUFFS',
            ], [
                'id' => 2,
                'description' => 'Criação de usuário sem IdUFFS',
            ], [
                'id' => 3,
                'description' => 'Atualização de usuário com IdUFFS',
            ], [
                'id' => 4,
                'description' => 'Atualização de usuário sem IdUFFS',
            ]]
        );
    }
}
