<?php

namespace Database\Factories;

use App\Models\Family;
use App\Models\Household;

use Illuminate\Database\Eloquent\Factories\Factory;

class FamilyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Family::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $household = Household::active()->pluck('id');

        return [
            'household_id' => $this->faker->randomElement($household),
            'family_no' => $this->faker->randomElement($household).'-'.$this->faker->numberBetween($min = 1, $max = 99999),
            'family_name' => $this->faker->lastName,
            'have_cell_radio_tv' => $this->faker->randomElement(['on', ''])
        ];
    }
}
