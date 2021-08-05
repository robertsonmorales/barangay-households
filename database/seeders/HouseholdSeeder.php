<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Household;

class HouseholdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Household::factory()->count(200)->create();
    }
}
