<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
                $this->call(\Database\Seeders\RolePermissionSeeder::class);
                $this->call(\Database\Seeders\PostSeeder::class);
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'David',
            'email' => 'david@example.com',
            'password' => Hash::make('asdf'),
        ]);

        User::factory()->create([
            'name' => 'Marta',
            'email' => 'marta@gmail.com',
            'password' => Hash::make('1234'),
        ]);

        User::factory()->create([
            'name' => 'Otro',
            'email' => 'otro@gmail.com',
            'password' => Hash::make('asdf'),
        ]);
    }
}
