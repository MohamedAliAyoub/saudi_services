<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'phone' => '01234567890', // Assuming you have a 'phone' column
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // Assuming you have a "role" column
        ]);

        // Create Employee User
        User::create([
            'name' => 'Employee User',
            'phone' => '01234567890', // Assuming you have a 'phone' column
            'email' => 'employee@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        // Create Client User
        User::create([
            'name' => 'Client User',
            'phone' => '01234567890', // Assuming you have a 'phone' column
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);
    }
}
