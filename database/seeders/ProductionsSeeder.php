<?php

namespace Database\Seeders;

use App\Models\Machine;
use App\Models\Employee;
use App\Models\Factory;
use App\Models\User;
use App\Models\Production;
use Illuminate\Database\Seeder;

class ProductionsSeeder extends Seeder
{
    public function run(): void
    {
        $machines = Machine::all();
        $employees = Employee::all();
        $factories = Factory::all();
        $managers = User::role('Manager')->get();

        for ($i = 1; $i <= 30; $i++) {

            Production::create([
                'batch_id' => 'BATCH-' . str_pad($i, 5, '0', STR_PAD_LEFT),

                'variety_type' => fake()->randomElement([
                    'Premium',
                    'Standard',
                    'Economy'
                ]),

                'total_length' => rand(100, 1000),
                'ready_production' => rand(50, 500),

                'machine_id' => $machines->random()->id,
                'employee_id' => $employees->random()->id,
                'factory_id' => $factories->random()->id,
                'manager_id' => $managers->random()->id,

                'shift_start' => now()->subHours(8),
                'shift_end' => now(),
                'status' => 1,
            ]);
        }
    }
}