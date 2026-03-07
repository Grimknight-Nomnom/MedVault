<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin User
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'middle_name' => null,
            'age' => '30',
            'date_of_birth' => '1990-01-01', 
            'usernumber' => '001', 
            'email' => 'admin@clinic.com',
            'email_verified_at' => now(), // <-- Auto verifies the email
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '+639123456789', 
            'address' => 'Clinic Head Office',
        ]);

        // 2. Create a Staff User
        User::create([
            'first_name' => 'Clinic',
            'last_name' => 'Staff',
            'middle_name' => null,
            'age' => '28',
            'date_of_birth' => '1995-08-20', 
            'usernumber' => '002', 
            'email' => 'staff@clinic.com',
            'email_verified_at' => now(), // <-- Auto verifies the email
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'phone' => '+639198765432', 
            'address' => 'Clinic Front Desk',
        ]);

        // 3. Create a Test Patient
        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'middle_name' => 'A.',
            'age' => '25',
            'date_of_birth' => '1998-05-15', 
            'usernumber' => '101', 
            'email' => 'patient@gmail.com',
            'email_verified_at' => now(), // <-- Auto verifies the email
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone' => '+639987654321', 
            'address' => '123 Sampaguita St, Manila',
        ]);

        // 4. Call the Medicine Seeder for Inventory Trends
        $this->call([
            MedicineSeeder::class,
        ]);
    }
}