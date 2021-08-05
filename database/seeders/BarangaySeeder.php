<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Barangay;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Barangay::factory()->count(50)->create();
    }
}
