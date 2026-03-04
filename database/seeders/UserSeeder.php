<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@busapp.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Operator users (owners of companies)
        User::create([
            'name'     => 'Green Express Operator',
            'email'    => 'greenexpress@busapp.com',
            'password' => Hash::make('password'),
            'role'     => 'operator',
        ]);

        User::create([
            'name'     => 'City Lines Operator',
            'email'    => 'citylines@busapp.com',
            'password' => Hash::make('password'),
            'role'     => 'operator',
        ]);

        // Regular passengers
        User::create([
            'name'     => 'Alice Rahman',
            'email'    => 'alice@example.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        User::create([
            'name'     => 'Bob Hossain',
            'email'    => 'bob@example.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);
    }
}
