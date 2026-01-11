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
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'middle_name' => null,
            'age' => 30,
            'usernumber' => '001', // Unique 3-digit code
            'email' => 'admin@clinic.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '09123456789',
            'address' => 'Clinic Head Office',
        ]);

        // Create a Test Patient
        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'middle_name' => 'A.',
            'age' => 25,
            'usernumber' => '101', // Unique 3-digit code
            'email' => 'patient@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone' => '09987654321',
            'address' => '123 Sampaguita St, Manila',
        ]);
    }
}