<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Owner
        $owner = User::updateOrCreate(
            ['email' => 'own@gmail.com'],
            [
                'name' => 'own',
                'phone_no' => '767543',
                'cnic' => '9876657',
                'address' => 'jhfdfd',
                'employee_details' => 'shdjfgdgh',
                'password' => Hash::make('123456'),
            ]
        );

        $owner->assignRole('Owner');

        // Manager
        $manager = User::updateOrCreate(
            ['email' => 'man@gmail.com'],
            [
                'name' => 'man',
                'phone_no' => '65453653',
                'cnic' => '86756764534',
                'address' => 'lahore',
                'employee_details' => 'yutyrt',
                'password' => Hash::make('123456'),
            ]
        );

        $manager->assignRole('Manager');

        // Employee
        $employee = User::updateOrCreate(
            ['email' => 'emp@gmail.com'],
            [
                'name' => 'Zain-Ul-Abdeen',
                'phone_no' => '03-000-000-111',
                'cnic' => '9876657654342',
                'address' => 'kamoki',
                'employee_details' => '////',
                'password' => Hash::make('123456'),
            ]
        );

        $employee->assignRole('Employee');

        // Employee
        $ali = User::updateOrCreate(
            ['email' => 'ali@test.com'],
            [
                'name' => 'Ali',
                'phone_no' => '876543',
                'cnic' => '765433',
                'address' => 'jhgf',
                'employee_details' => 'hgfd',
                'password' => Hash::make('123456'),
            ]
        );

        $ali->assignRole('Owner');

        // Employee
        $aaa = User::updateOrCreate(
            ['email' => 'aaa@gmail.com'],
            [
                'name' => 'aaa',
                'phone_no' => '555555555555',
                'cnic' => '44444444444',
                'address' => 'hgff',
                'employee_details' => 'ghh',
                'password' => Hash::make('123456'),
            ]
        );

        $aaa->assignRole('Employee');
    }
}