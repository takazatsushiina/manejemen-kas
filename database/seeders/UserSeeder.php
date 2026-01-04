<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PettyCash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo users
        User::create([
            'name' => 'User Demo',
            'username' => 'user',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Admin Demo',
            'username' => 'admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Initialize petty cash
        PettyCash::create([
            'current_balance' => 5000000,
            'initial_balance' => 5000000,
            'notes' => 'Saldo awal kas kecil',
        ]);
    }
}
