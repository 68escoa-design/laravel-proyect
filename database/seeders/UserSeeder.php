<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gimnasio.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Trainer',
            'email' => 'trainer@gimnasio.com',
            'password' => Hash::make('password'),
            'role' => 'trainer',
        ]);

        User::create([
            'name' => 'Cliente',
            'email' => 'cliente@gimnasio.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);
    }
}
