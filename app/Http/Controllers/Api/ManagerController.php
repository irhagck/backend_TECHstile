<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Factory;
use App\Models\Production;
use App\Models\Machine;
use App\Models\Employee;
use App\Models\User;

use Illuminate\Http\Request;

class ManagerController extends Controller
{
    // ==========================
    // DASHBOARD
    // ==========================
 public function dashboard($factoryId)
{
    $factory = Factory::find($factoryId);

    if (!$factory) {
        return response()->json([
            "message" => "Factory not found"
        ],404);
    }

    $productions = Production::where('factory_id', $factoryId)
        ->whereNotIn('status', [3, 5]) // rejected productions exclude karo
        ->get();
    $varieties = $productions
        ->groupBy('variety_type')
        ->map(function ($item, $name) {
            return [
                "variety_type" => $name,
                "ready_production" => $item->sum('ready_production')
            ];
        })
        ->values();

    return response()->json([
        "status" => true,
        "factory" => $factory,

        "today_units" => $productions
            ->where('created_at', '>=', now()->startOfDay())
            ->sum('ready_production'),

        "weekly_units" => $productions
            ->where('created_at', '>=', now()->subDays(7))
            ->sum('ready_production'),

        "total_varieties" => $varieties->count(),

        "machines_count" => Machine::where(
            'factory_id',
            $factoryId
        )->count(),

        "employees_count" => Employee::where(
            'factory_id',
            $factoryId
        )->count(),

        "varieties" => $varieties
    ]);
}

    // ==========================
    // MACHINES
    // ==========================
    public function machines($factoryId)
    {
        $factory = Factory::find($factoryId);

        if (!$factory) {
            return response()->json([
                "message" => "Factory not found"
            ], 404);
        }

        $machines = Machine::where(
            'factory_id',
            $factoryId
        )->get();

        // ✅ "Active" machine = jis par aaj (last 24 ghantay) production scan/entry hui ho
        $activeMachineIds = Production::where('factory_id', $factoryId)
            ->where('created_at', '>=', now()->subHours(24))
            ->pluck('machine_id')
            ->unique();

        $machines = $machines->map(function ($m) use ($activeMachineIds) {
            $arr = $m->toArray();
            $arr['is_active'] = $activeMachineIds->contains($m->id);
            return $arr;
        });

        return response()->json([
            "status" => true,
            "machines" => $machines,
            "total_machines"  => $machines->count(),
            "active_machines" => $activeMachineIds->count(),
        ]);
    }

    // ==========================
    // EMPLOYEES
    // ==========================
    public function employees($factoryId)
    {
        $factory = Factory::find($factoryId);

        if (!$factory) {
            return response()->json([
                "message" => "Factory not found"
            ], 404);
        }

        $employees = Employee::with('user')
            ->where('factory_id', $factoryId)
            ->get();

        // ✅ "Active" employee = jisne aaj (last 24 ghantay) machine scan kar ke
        // production submit ki ho
        $activeEmployeeIds = Production::where('factory_id', $factoryId)
            ->where('created_at', '>=', now()->subHours(24))
            ->pluck('employee_id')
            ->unique();

        $employees = $employees->map(function ($e) use ($activeEmployeeIds) {
            $arr = $e->toArray();
            $arr['is_active'] = $activeEmployeeIds->contains($e->id);
            return $arr;
        });

        return response()->json([
            "status" => true,
            "employees" => $employees,
            "total_employees"  => $employees->count(),
            "active_employees" => $activeEmployeeIds->count(),
        ]);
    }

    // ==========================
    // EMPLOYEE DETAILS
    // ==========================
    public function employeeDetails($employeeId)
    {
         \Log::info("Employee ID Received = ".$employeeId);
        $employee = Employee::with('user')
            ->find($employeeId);

        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found'
            ], 404);
        }

        $productions = Production::where(
            'employee_id',
            $employee->id
        )->get();

        return response()->json([

            'employee_id' => $employee->id,

            'name' => $employee->user?->name,

            'email' => $employee->user?->email,

            'shift_start' => $employee->shift_starttime,

            'shift_end' => $employee->shift_endtime,

            'total_production' => $productions->sum('ready_production'),

            'total_waste' => $productions->sum('waste_production'),

            'machines_worked' => $productions
                ->pluck('machine_id')
                ->unique()
                ->count(),

            'total_entries' => $productions->count(),

            'created_at' => $employee->created_at,
        ]);
    }
    //manager profile
    public function profile($userId)
{
    $user = User::find($userId);

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'Manager not found'
        ], 404);
    }

    $factoryId = Production::where(
        'manager_id',
        $userId
    )->value('factory_id');

    $totalEmployees = Employee::where(
        'factory_id',
        $factoryId
    )->count();

    $totalProduction = Production::where(
        'factory_id',
        $factoryId
    )->sum('ready_production');

    $factoryName = Factory::where('id', $factoryId)->value('name');

    return response()->json([
        'status' => true,
        'data' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone_no' => $user->phone_no,
            'address' => $user->address,
            'pic' => $user->pic,
            'factory_id' => $factoryId,
            'factory_name' => $factoryName,
            'total_employees' => $totalEmployees,
            'total_production' => $totalProduction,
        ]
    ]);
}
}