<?php

namespace Database\Seeders;

use App\Models\Machine;
use App\Models\Factory;
use Illuminate\Database\Seeder;

class MachinesSeeder extends Seeder
{
    public function run(): void
    {
        $factories = Factory::all();

        foreach ($factories as $factory) {

            for ($i = 1; $i <= 5; $i++) {

                Machine::create([
                    'factory_id' => $factory->id,
                    'machine_id' => 'MC-' . rand(1000, 9999),
                    'machine_type' => fake()->randomElement([
                        'Cutting',
                        'Packing',
                        'Printing',
                        'Welding'
                    ]),
                    'time' => now(),
                ]);
            }
        }
    }
}