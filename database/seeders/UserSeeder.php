<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@responde.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Responder
        User::create([
            'name' => 'Responder One',
            'username' => 'responder',
            'email' => 'responder@responde.com',
            'password' => Hash::make('responder123'),
            'role' => 'responder',
        ]);

        // Regular User
        User::create([
            'name' => 'Regular User',
            'username' => 'user',
            'email' => 'user@responde.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);
    }
}
