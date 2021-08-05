<?php

namespace Database\Factories;

use App\Models\Household;
use App\Models\House;
use Illuminate\Database\Eloquent\Factories\Factory;

class HouseholdFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Household::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $house = House::active()->pluck('id');

        return [
            'house_id' => $this->faker->randomElement($house),
            'household_no' => $this->faker->randomElement($house).'-'.$this->faker->numberBetween($min = 1, $max = 99999),
            'land_ownership' => $this->faker->name,
            'cr' => 'shared to',
            'shared_to' => $this->faker->numberBetween($min = 1, $max = 20),
            'electricity_connection' => $this->faker->stateAbbr.'ELCO',
            'disaster_kit' => $this->faker->randomElement(['on', '']),
            'praticing_waste_segregation' => $this->faker->randomElement(['on', '']),
        ];
    }
}
