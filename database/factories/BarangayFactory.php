<?php

namespace Database\Factories;

use App\Models\Barangay;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangayFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Barangay::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'barangay_code' => $this->faker->unixTime($max = 'now'),
            'barangay_name' => $this->faker->city
        ];
    }
}
