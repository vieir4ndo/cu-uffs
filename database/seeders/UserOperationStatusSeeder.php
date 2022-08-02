<?php

namespace Database\Seeders;

use App\Models\UserOperationStatus;
use Illuminate\Database\Seeder;

class UserOperationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserOperationStatus::truncate();

        UserOperationStatus::insert(
            [[
                'id' => 1,
                'description' => 'Solicitado',
            ], [
                'id' => 2,
                'description' => 'Iniciado',
            ], [
                'id' => 3,
                'description' => 'Validação Credenciais do IdUFFS',
            ], [
                'id' => 4,
                'description' => 'Validação da Matrícula/SIAPE',
            ], [
                'id' => 5,
                'description' => 'Validação da Foto de Perfil',
            ], [
                'id' => 6,
                'description' => 'Geração do Código de Barras',
            ], [
                'id' => 7,
                'description' => 'Sucesso',
            ], [
                'id' => 8,
                'description' => 'Falha',
            ]]
        );
    }
}
