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
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
            'name' => 'Admin',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::updateOrCreate(
            [ 'email' => 'manager@example.com'],
            [
            'name' => 'Operations Manager',
            'password' => Hash::make('password'),
            'role' => 'manager'
        ]);

        User::updateOrCreate(
            [ 'email' => 'viewer@example.com'],
            [
                'name' => 'Read Only User',
                'password' => Hash::make('password'),
                'role' => 'viewer'
            ]);
    }
}
