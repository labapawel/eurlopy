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
       $dane =  User::firstOrCreate([
            'name' => 'Paweł Łaba',
            'email' => 'labapawel@gmail.com',
              'password' => \Hash::make('123456'),
            'role' => [2], // Super admin
            'active' => true,
            'expired_at' => null, // No expiration date
        ]);

        // $dane->password= "123456";
        $dane->save();

    }
}
