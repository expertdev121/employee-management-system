<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Shift;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'department' => 'Management',
            'hourly_rate' => 25.00,
        ]);

        // Create employee users
        User::create([
            'name' => 'John Doe',
            'email' => 'employee1@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'status' => 'active',
            'department' => 'Operations',
            'hourly_rate' => 15.00,
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'employee2@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'status' => 'active',
            'department' => 'Operations',
            'hourly_rate' => 15.00,
        ]);

        // Create sample shifts
        Shift::create([
            'shift_name' => 'Monday Shift',
            'shift_type' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'max_capacity' => 5,
            'description' => 'Standard Monday shift',
            'location' => 'Main Office',
            'status' => 'active',
        ]);

        Shift::create([
            'shift_name' => 'Tuesday Shift',
            'shift_type' => 'tuesday',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'max_capacity' => 5,
            'description' => 'Standard Tuesday shift',
            'location' => 'Main Office',
            'status' => 'active',
        ]);

        Shift::create([
            'shift_name' => 'Wednesday Shift',
            'shift_type' => 'wednesday',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'max_capacity' => 5,
            'description' => 'Standard Wednesday shift',
            'location' => 'Main Office',
            'status' => 'active',
        ]);

        Shift::create([
            'shift_name' => 'Thursday Shift',
            'shift_type' => 'thursday',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'max_capacity' => 5,
            'description' => 'Standard Thursday shift',
            'location' => 'Main Office',
            'status' => 'active',
        ]);

        Shift::create([
            'shift_name' => 'Friday Shift',
            'shift_type' => 'friday',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'max_capacity' => 5,
            'description' => 'Standard Friday shift',
            'location' => 'Main Office',
            'status' => 'active',
        ]);

        Shift::create([
            'shift_name' => 'Saturday Shift',
            'shift_type' => 'saturday',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'max_capacity' => 3,
            'description' => 'Standard Saturday shift',
            'location' => 'Main Office',
            'status' => 'active',
        ]);

        Shift::create([
            'shift_name' => 'Sunday Shift',
            'shift_type' => 'sunday',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'max_capacity' => 3,
            'description' => 'Standard Sunday shift',
            'location' => 'Main Office',
            'status' => 'active',
        ]);
    }
}
