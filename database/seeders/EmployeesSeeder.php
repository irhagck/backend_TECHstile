<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeesSeeder extends Seeder
{
    public function run(): void
    {
        $employees = User::role('Employee')->get();

        foreach ($employees as $index => $user) {

            Employee::create([
                'employee_id' => 'EMP-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'shift_starttime' => '08:00:00',
                'shift_endtime' => '17:00:00',
                'user_id' => $user->id,
                'timestamp' => now(),
            ]);
        }
    }
}