<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Factory;
use App\Models\Production;
use App\Models\Machine;
use App\Models\Employee;
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
            ], 404);
        }

        $productions = Production::where(
            'factory_id',
            $factoryId
        )->get();

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
                ->sum('total_length'),

            "weekly_units" => $productions
                ->where('created_at', '>=', now()->subDays(7))
                ->sum('total_length'),

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

        return response()->json([
            "status" => true,
            "machines" => $machines
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

        return response()->json([
            "status" => true,
            "employees" => $employees
        ]);
    }

    // ==========================
    // EMPLOYEE DETAILS
    // ==========================
    public function employeeDetails($employeeId)
    {
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
}