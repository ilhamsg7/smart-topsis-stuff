<?php

namespace Database\Seeders;

use App\Models\Criterion;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CriterionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Criterion::factory()->count(6)->create();
    }
}
