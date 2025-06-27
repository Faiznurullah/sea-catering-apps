<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin User
        User::create([
            'name' => 'Admin SEA Catering',
            'email' => 'admin@seacatering.com',
            'email_verified_at' => Carbon::now(),
            'role' => 'admin',
            'password' => Hash::make('admin123'),
            'phone' => '08123456789',
            'city' => 'Jakarta',
            'national' => 'Indonesia',
            'language' => 'id',
            'point' => 0,
        ]);

        // Create Regular User 1
        User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'email_verified_at' => Carbon::now(),
            'role' => 'user',
            'password' => Hash::make('user123'),
            'phone' => '08123456001',
            'city' => 'Jakarta',
            'national' => 'Indonesia',
            'language' => 'en',
            'point' => 50,
        ]);

        // Create Regular User 2
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'email_verified_at' => Carbon::now(),
            'role' => 'user',
            'password' => Hash::make('user123'),
            'phone' => '08123456002',
            'city' => 'Bandung',
            'national' => 'Indonesia',
            'language' => 'id',
            'point' => 75,
        ]);

        // Create Regular User 3
        User::create([
            'name' => 'Robert Johnson',
            'email' => 'robert.johnson@example.com',
            'email_verified_at' => Carbon::now(),
            'role' => 'user',
            'password' => Hash::make('user123'),
            'phone' => '08123456003',
            'city' => 'Surabaya',
            'national' => 'Indonesia',
            'language' => 'en',
            'point' => 30,
        ]);

        // Create Regular User 4
        User::create([
            'name' => 'Maria Garcia',
            'email' => 'maria.garcia@example.com',
            'email_verified_at' => Carbon::now(),
            'role' => 'user',
            'password' => Hash::make('user123'),
            'phone' => '08123456004',
            'city' => 'Yogyakarta',
            'national' => 'Indonesia',
            'language' => 'id',
            'point' => 100,
        ]);

        // Create Regular User 5
        User::create([
            'name' => 'David Wilson',
            'email' => 'david.wilson@example.com',
            'email_verified_at' => Carbon::now(),
            'role' => 'user',
            'password' => Hash::make('user123'),
            'phone' => '08123456005',
            'city' => 'Medan',
            'national' => 'Indonesia',
            'language' => 'en',
            'point' => 25,
        ]);
 
    }
}
