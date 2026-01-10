<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@clinic.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '09123456789',
            'address' => 'Clinic Head Office',
        ]);

        // Create a Test Patient
        User::create([
            'name' => 'John Doe',
            'email' => 'patient@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone' => '09987654321',
            'address' => '123 Sampaguita St, Manila',
        ]);
    }
}