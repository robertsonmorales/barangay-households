<?php

namespace Database\Factories;

use App\Models\Barangay;
use App\Models\House;
use Illuminate\Database\Eloquent\Factories\Factory;

class HouseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = House::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $barangay = Barangay::active()->pluck('id');

        return [
            'barangay_id' => $this->faker->randomElement($barangay),
            'house_no' => $this->faker->randomElement($barangay).'-'.$this->faker->numberBetween($min = 1, $max = 99999),
            'house_roof' => $this->faker->randomElement(House::HOUSE_DETAILS),
            'house_wall' => $this->faker->randomElement(House::HOUSE_DETAILS),
            'building_permit' => $this->faker->randomElement(['on', '']),
            'occupancy_permit' => $this->faker->randomElement(['on', '']),
            'date_constructed' => $this->faker->dateTime($max = 'now', $timezone = null),
        ];
    }
}
