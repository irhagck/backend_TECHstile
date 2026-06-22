<?php

namespace Database\Seeders;

use App\Models\Factory;
use Illuminate\Database\Seeder;

class FactoriesSeeder extends Seeder
{
    public function run(): void
    {
        Factory::insert([
            [
                'name' => 'Gujranwala Factory',
                'address' => 'GT Road Gujranwala',
                'city' => 'Gujranwala',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lahore Factory',
                'address' => 'Ferozepur Road Lahore',
                'city' => 'Lahore',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}