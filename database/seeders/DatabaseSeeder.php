<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CriterionSeeder;
use Database\Seeders\AlternativeSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AlternativeSeeder::class,
            CriterionSeeder::class,
        ]);
    }
}
