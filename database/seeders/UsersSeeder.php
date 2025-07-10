<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Paweł Łaba',
            'email' => 'labapawel@gmail.com',
            'password' => bcrypt('123456'),
            'role' => [2  ], // Super admin
            'active' => true,
            'expired_at' => null, // No expiration date
        ]);

    }
}
