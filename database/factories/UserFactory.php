<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Crypt;

use Carbon\Carbon;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => Crypt::encryptString($this->faker->firstName),
            'last_name' => Crypt::encryptString($this->faker->lastName),
            'username' => $this->faker->userName,
            'contact_number' => Crypt::encryptString("+639".mt_rand(100000000, 999999999)),
            'email' => Crypt::encryptString($this->faker->unique()->safeEmail),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'address' => $this->faker->address,
            'account_status' => 1,
            'ip' => $this->faker->ipv4,
            'created_at' => now(),
        ];
    }
}
