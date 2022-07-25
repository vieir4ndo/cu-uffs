<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\OperationSeeder;
use Database\Seeders\UserTypeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTypeSeeder::class);
        $this->call(OperationSeeder::class);
    }
}

