<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Production;
use App\Models\Machine;
use Carbon\Carbon;
use App\Models\Attendence;
use App\Models\User;
use App\Models\Factory;
class EmployeeDashController extends Controller
{

// employee dashboard
 public function dashboard(Request $request, $id)
{
    $employee = Employee::with('user')
        ->where('user_id', $id)
        ->first();

    if (!$employee) {
        return response()->json([
            'message' => 'Employee not found'
        ], 404);
    }

    // Employee ki factory
    $factoryId = $employee->factory_id;

    // Factory ka manager
    $managerId = Production::where('factory_id', $factoryId)
        ->whereNotNull('manager_id')
        ->latest()
        ->value('manager_id');

    // ✅ Employee ko jitni bhi machines assign hain (koi bhi status — pending ho ya approved),
    // taake dashboard sirf production approve hony ka wait na kare
    $assignedMachineIds = Production::where('employee_id', $employee->id)
        ->distinct()
        ->pluck('machine_id');

    $data = $assignedMachineIds->map(function ($machineId) use ($employee) {

        $latest = Production::with(['machineemploye', 'employeedetails.user'])
            ->where('employee_id', $employee->id)
            ->where('machine_id', $machineId)
            ->latest()
            ->first();

        if (!$latest) return null;

        $readyProduction = Production::where('employee_id', $employee->id)
            ->where('machine_id', $machineId)
            ->where('batch_id', $latest->batch_id)
            ->sum('ready_production');

        $totalLength = $latest->total_length;

        $progress = $totalLength > 0
            ? round(($readyProduction / $totalLength) * 100, 2)
            : 0;

        return [
            'machine_id'     => $latest->machine_id,
            'machine_name'   => $latest->machineemploye?->machine_name ?? '',
            'machine_type'   => $latest->machineemploye?->machine_type ?? '',
            'employee_name'  => $latest->employeedetails?->user?->name ?? '',
            'variety_type'   => $latest->variety_type,
            'batch_id'       => $latest->batch_id,
            'ready_production' => $readyProduction,
            'total_length'   => $totalLength,
            'progress'       => $progress,
            'status'         => $latest->status,
            'factory_id'     => $latest->factory_id,
        ];
    })->filter()->values();

    // ✅ Daily/Weekly = SIRF approved production (status 2 = manager approved, 4 = owner approved),
    // sab machines mila kar
    $approvedQuery = Production::where('employee_id', $employee->id)
        ->whereIn('status', [2, 4]);

    $dailyApproved = (clone $approvedQuery)
        ->whereDate('created_at', Carbon::today())
        ->sum('ready_production');

    $weeklyApproved = (clone $approvedQuery)
        ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->sum('ready_production');

    return response()->json([
        'employee_name' =>
            $employee->user?->name ?? '',

        'factory_id' =>
            $factoryId,

        'manager_id' =>
            $managerId,

        'total_machines' =>
            $data->count(),

        'daily_ready_production'  => $dailyApproved,
        'weekly_ready_production' => $weeklyApproved,

        // ✅ backward-compat field names (purane frontend ke liye)
        'total_production' =>
            $data->sum('total_length'),

        'total_ready_production' =>
            $data->sum('ready_production'),

        'machines' =>
            $data
    ]);
}
//employee side machine details
 public function machineDetails(Request $request, $id)
{
    $user = $request->user();
    $employee = Employee::where('user_id', $user->id)->first();

    if (!$employee) {
        return response()->json(['message' => 'Employee not found'], 404);
    }

    $machine = Machine::find($id);
    if (!$machine) {
        return response()->json(['message' => 'Machine not found'], 404);
    }

    // ✅ Latest production — employee + machine ke basis pe
    $production = Production::with(['employeedetails.user'])
        ->where('machine_id', $id)
        ->where('employee_id', $employee->id)
        ->latest()
        ->first();

    if (!$production) {
        return response()->json(['message' => 'No production found'], 404);
    }

    if (!$production->batch_id) {
        return response()->json([
            'message' => 'No production batch assigned to this machine yet. Ask owner to assign a batch first.',
        ], 422);
    }

    // ✅ Sirf isi batch ka sum — is employee ka apna contribution
    $totalReadyProduction = Production::where('machine_id', $id)
        ->where('employee_id', $employee->id)
        ->where('batch_id', $production->batch_id)
        ->sum('ready_production');

    // ✅ Remaining ab SHARED hai — is batch ki DONO shift-employees ki total ready+waste
    //    machine ke total_length se nikaali jati hai (stale per-row 'remaining' field trust nahi karte)
    $batchReadyTotal = Production::where('machine_id', $id)
        ->where('batch_id', $production->batch_id)
        ->sum('ready_production');

    $batchWasteTotal = Production::where('machine_id', $id)
        ->where('batch_id', $production->batch_id)
        ->sum('waste_production');

    $remaining = max(0, $production->total_length - ($batchReadyTotal + $batchWasteTotal));
   $canAddProduction = $remaining > 0;

    $dailyProduction = Production::where('machine_id', $id)
        ->whereDate('created_at', Carbon::today())
        ->sum('ready_production');

    $weeklyProduction = Production::where('machine_id', $id)
        ->whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])
        ->sum('ready_production');

    $yearlyProduction = Production::where('machine_id', $id)
        ->whereYear('created_at', Carbon::now()->year)
        ->sum('ready_production');

   //  Check today attendance (timestamp based)
$alreadyMarkedToday = Attendence::where('employee_id', $employee->id)
    ->where('machine_id', $id)
    ->where('type', 'IN')
    ->whereDate('timestamp', Carbon::today())
    ->exists();

    return response()->json([
        'machine_id'         => $machine->id,
        'machine_type'       => $machine->machine_type,
        'status'             => $machine->status,
        'employee_id'        => $production->employeedetails?->id,
        'employee_name'      => $production->employeedetails?->user?->name,
        'shift_start' => $production->employeedetails?->shift_starttime,
        'shift_end'   => $production->employeedetails?->shift_endtime,
        // 'shift_start'        => $production->shift_start,
        // 'shift_end'          => $production->shift_end,
        'variety_type'       => $production->variety_type,
        'total_length'       => $production->total_length,
        'batch_id'           => $production->batch_id,  
        'ready_production'   => $totalReadyProduction,
        'remaining'          => $remaining,
        'can_add_production' => $canAddProduction,
        'daily_production'   => $dailyProduction,
        'weekly_production'  => $weeklyProduction,
        'yearly_production'  => $yearlyProduction,
       'already_marked_today' => $alreadyMarkedToday,
    ]);
}
// employee profile page 
public function profile(Request $request, $id)
{
    $user = User::with('roles')->find($id);

    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }

    $employee = Employee::where('user_id', $id)->first();

    $totalMachines        = 0;
    $totalProduction      = 0;
    $totalReadyProduction = 0;
    $attendanceCount      = 0;
    $data                 = collect();

   if ($employee) {


    // 🔥 employee ki current factory + manager find karo
    $lastProduction = Production::where(
        'employee_id',
        $employee->id
    )
    ->latest()
    ->first();


    $factoryId = $lastProduction?->factory_id;
    $managerId = $lastProduction?->manager_id;



    // 🔥 ONLY SAME EMPLOYEE + SAME FACTORY + SAME MANAGER
    $productions = Production::with([
        'machineemploye',
        'employeedetails.user'
    ])
    ->where('employee_id', $employee->id)

    ->where('factory_id',$factoryId)

    ->where('manager_id',$managerId)

    ->where('status', 2)

    ->whereBetween('created_at', [
        Carbon::now()->subDays(7),
        Carbon::now()
    ])
    ->get();
        // group machine + variety + batch
        $grouped = $productions->groupBy(function ($item) {
            return $item->machine_id . '_'
                . $item->variety_type . '_'
                . $item->batch_id;
        });

        $data = $grouped->map(function ($group) {

            $first = $group->first();

            $readyProduction = $group->sum('ready_production');
            $totalLength     = $first->total_length;

            $progress = ($totalLength > 0)
                ? round(($readyProduction / $totalLength) * 100, 2)
                : 0;

            return [
                'machine_id'       => $first->machine_id,
                'machine_type'     => $first->machineemploye?->machine_type ?? '',
                'machine_status'   => $first->machineemploye?->status ?? '',
                'employee_name'    => $first->employeedetails?->user?->name ?? '',
                'variety_type'     => $first->variety_type ?? '',
                'batch_id'         => $first->batch_id ?? '',
                'ready_production' => $readyProduction,
                'total_length'     => $totalLength,
                'progress'         => $progress,
                'from_date'        => Carbon::now()->subDays(7)->format('Y-m-d'),
                'to_date'          => Carbon::now()->format('Y-m-d'),
            ];
        })->values();

        $totalMachines        = $data->count();
        $totalProduction      = $data->sum('total_length');
        $totalReadyProduction = $data->sum('ready_production');
        $factoryName          = $factoryId ? Factory::where('id', $factoryId)->value('name') : null;
        $managerName          = $managerId ? User::where('id', $managerId)->value('name') : null;
        $attendanceCount      = Attendence::where('employee_id', $employee->id)->count();
    }

    return response()->json([
        'success' => true,
        'data' => [
            ...$user->toArray(),
            'employee_id'            => $employee?->id,
            'factory_id'             => $employee?->factory_id ?? ($factoryId ?? null),
            'factory_name'           => $factoryName ?? null,
            'manager_name'           => $managerName ?? null,
            'role'                   => 'Employee',
            'total_machines'         => $totalMachines,
            'total_production'       => $totalProduction,
            'total_ready_production' => $totalReadyProduction,
            'attendance_count'       => $attendanceCount,
            'machines'               => $data,
        ]
    ]);
}
// employee history functions
public function employeeHistory($id)
{
    $employee = Employee::where('user_id', $id)->first();
    if (!$employee) {
        $employee = Employee::find($id);
    }

    if (!$employee) {
        return response()->json([
            'message' => 'Employee not found'
        ], 404);
    }

    // Base query for this employee
    $baseQuery = Production::where('employee_id', $employee->id);

    $pending = (clone $baseQuery)->with('machine')
        ->whereIn('status', [1, 2])
        ->latest()
        ->get();

    $approved = (clone $baseQuery)->with('machine')
        ->where('status', 4)
        ->latest()
        ->get();

    $completed = $approved
        ->groupBy(function ($item) {
            return $item->machine_id . '_'
                . $item->variety_type . '_'
                . $item->batch_id;
        })
        ->map(function ($group) {
            $first = $group->first();
            return [
                'machine_id'       => $first->machine_id,
                'machine_type'     => $first->machine?->machine_type ?? '',
                'variety_type'     => $first->variety_type,
                'batch_id'         => $first->batch_id,
                'ready_production' => (float) $group->sum('ready_production'),
                'total_length'     => (float) $first->total_length,
                'status'           => $first->status,
            ];
        })
        ->values();

    $daily = (clone $baseQuery)
        ->where('status', 4)
        ->whereDate('created_at', today())
        ->sum('ready_production');

    $weekly = (clone $baseQuery)
        ->where('status', 4)
        ->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])
        ->sum('ready_production');

    $monthly = (clone $baseQuery)
        ->where('status', 4)
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('ready_production');

    return response()->json([
        'employee_id' => $employee->id,
        'pending'     => $pending,
        'completed'   => $completed,
        'daily'       => (float) $daily,
        'weekly'      => (float) $weekly,
        'monthly'     => (float) $monthly,
    ]);
}
}