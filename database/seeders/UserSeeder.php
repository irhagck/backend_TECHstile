<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('Owner');

        for ($i = 1; $i <= 3; $i++) {

            $manager = User::updateOrCreate(
                ['email' => "manager{$i}@gmail.com"],
                [
                    'name' => "Manager {$i}",
                    'password' => Hash::make('123456'),
                    'email_verified_at' => now(),
                ]
            );

            $manager->assignRole('Manager');
        }

        for ($i = 1; $i <= 10; $i++) {

            $employee = User::updateOrCreate(
                ['email' => "employee{$i}@gmail.com"],
                [
                    'name' => "Employee {$i}",
                    'password' => Hash::make('123456'),
                    'email_verified_at' => now(),
                ]
            );

            $employee->assignRole('Employee');
        }
    }
}