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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'David',
            'email' => 'david@example.com',
            'password' => Hash::make('asdf'),
            'email_verified_at' => now(),
        ]);
    }
}
