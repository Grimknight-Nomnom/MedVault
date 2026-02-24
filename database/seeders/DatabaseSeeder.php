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
            'date_of_birth' => '1990-01-01', // ADDED THIS
            'usernumber' => '001', 
            'email' => 'admin@clinic.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '+639123456789', // Updated to match +639 format
            'address' => 'Clinic Head Office',
        ]);

        // Create a Test Patient
        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'middle_name' => 'A.',
            'age' => 25,
            'date_of_birth' => '1998-05-15', // ADDED THIS
            'usernumber' => '101', 
            'email' => 'patient@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone' => '+639987654321', // Updated to match +639 format
            'address' => '123 Sampaguita St, Manila',
        ]);
    }
}