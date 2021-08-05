<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\House;

class HouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        House::factory()->count(100)->create();
    }
}
