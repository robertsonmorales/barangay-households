<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(20)->create();

        User::insert([
            'first_name' => Crypt::encryptString('test'),
            'last_name' => Crypt::encryptString('case'),
            'username' => 'test',
            'contact_number' => Crypt::encryptString('09123456789'),
            'email' => Crypt::encryptString('test@gmail.com'),
            'password' => Hash::make('7ujm&UJM'),
            'user_level_code' => 'dev',
            'created_by' => 1,
            'created_at' => now()
        ]);
    }
}
