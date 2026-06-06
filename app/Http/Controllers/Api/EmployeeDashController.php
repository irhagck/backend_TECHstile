<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Production;
use App\Models\Machine;
class EmployeeDashController extends Controller
{
    public function dashboard($id)
    {
        $todayProduction = DB::table('productions')
    ->where('employee_id', $id)
    ->whereDate('created_at', now())
    ->sum('total_length');

$weeklyProduction = DB::table('productions')
    ->where('employee_id', $id)
    ->whereBetween('created_at', [now()->subDays(7), now()])
    ->sum('total_length');

        $machineId = Production::where('employee_id', $id)
            ->latest()
            ->value('machine_id');

        $assignedMachine = Machine::where('id', $machineId)
            ->first();

        $machineCount = DB::table('machines')->count();

        return response()->json([
            "today_production" => $todayProduction,
            "weekly_production" => $weeklyProduction,
            "assigned_machine" => $assignedMachine->name ?? "Not Assigned",
            "machine_count" => $machineCount,
            "efficiency" => 94
        ]);
    }
}