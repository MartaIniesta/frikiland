<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'David',
            'username' => 'david',
            'email' => 'david@example.com',
            'password' => Hash::make('asdf'),
        ]);

        User::factory()->create([
            'name' => 'Marta',
            'username' => 'marta',
            'email' => 'marta@gmail.com',
            'password' => Hash::make('1234'),
        ]);

        User::factory()->create([
            'name' => 'Otro',
            'username' => 'otro',
            'email' => 'otro@gmail.com',
            'password' => Hash::make('asdf'),
        ]);
    }
}
