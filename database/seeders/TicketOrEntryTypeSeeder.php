<?php

namespace Database\Seeders;

use App\Models\TicketOrEntryType;
use Illuminate\Database\Seeder;

class TicketOrEntryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TicketOrEntryType::truncate();

        TicketOrEntryType::insert([[
                'id' => 1,
                'description' => 'Servidor',
            ], [
                'id' => 2,
                'description' => 'Estudante',
            ], [
                'id' => 3,
                'description' => 'Servidor Terceirizado',
            ], [
                'id' => 4,
                'description' => 'Visitante',
            ]]
        );
    }
}
