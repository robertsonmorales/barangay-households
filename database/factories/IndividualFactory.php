<?php

namespace Database\Factories;

use App\Models\Individual;
use App\Models\Family;
use Illuminate\Database\Eloquent\Factories\Factory;

class IndividualFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Individual::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $family = Family::active()->pluck('id');

        return [
            'family_id' => $this->faker->randomElement($family),
            'individual_no' => $this->faker->randomElement($family).'-'.$this->faker->numberBetween($min = 1, $max = 99999),
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->lastName,
            'suffix' => $this->faker->randomElement(['', $this->faker->suffix]),
            'gender' => $this->faker->randomElement([null, 'male', 'female']),
            'birthdate' => $this->faker->dateTime($max = 'now', $timezone = null),
            'ethnicity' => $this->faker->randomElement(['aeta', 'agta', 'itim', 'puti']),
            'relationship' => $this->faker->randomElement([
                'father-in-law', 'mother', 'son', 'daughter', 'grandfather', 'grandmother', 'grandson', 'granddaughter', 'step-son', 'step-daughter', 'step-mother', 'step-father', 'relative'
            ]),
            'marital_status' => $this->faker->randomElement([
                'single', 'married', 'separated', 'widowed', 'live-in'
            ])
        ];
    }
}
