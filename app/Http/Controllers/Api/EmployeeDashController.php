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

    // Employee factory
    $factoryId = $employee->factory_id;

    // Factory manager
    $managerId = Production::where('factory_id', $factoryId)
        ->whereNotNull('manager_id')
        ->latest()
        ->value('manager_id');

    // Employee show all machines that assign 
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

    // Daily and Weekly only approved production (status 2 = manager approved, 4 = owner approved)

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

    // Latest production employee and machine base
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

    // Only this batch sum
    $totalReadyProduction = Production::where('machine_id', $id)
        ->where('employee_id', $employee->id)
        ->where('batch_id', $production->batch_id)
        ->sum('ready_production');

    //Remaining is shared this batch have both employees remaining and wastage minus ready production
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

   //  Check today attendance 
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


    //Employee current factory id and manager id
    $lastProduction = Production::where(
        'employee_id',
        $employee->id
    )
    ->latest()
    ->first();


    $factoryId = $lastProduction?->factory_id;
    $managerId = $lastProduction?->manager_id;



    //only same empoyee, factory, and manager
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
        // group machine, variety, and batch
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
        $attendanceCount      = Attendence::where('employee_id', $employee->id)->count();
    }

    return response()->json([
        'success' => true,
        'data' => [
            ...$user->toArray(),
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
        return response()->json([
            'message' => 'Employee not found'
        ], 404);
    }

    // Latest assignment
    $lastProduction = Production::where(
        'employee_id',
        $employee->id
    )
    ->latest()
    ->first();

    if (!$lastProduction) {
        return response()->json([
            'pending'   => [],
            'completed' => [],
            'daily'     => 0,
            'weekly'    => 0,
            'monthly'   => 0,
        ]);
    }

    $factoryId = $lastProduction->factory_id;
    $managerId = $lastProduction->manager_id;

   
    // PENDING (Status 1)
   

    $pending = Production::with('machine')
        ->where('employee_id', $employee->id)
        ->where('factory_id', $factoryId)
        ->where('manager_id', $managerId)
        ->where('status', 1)
        ->latest()
        ->get();

    // COMPLETED (Status 4)

    $approved = Production::with('machine')
        ->where('employee_id', $employee->id)
        ->where('factory_id', $factoryId)
        ->where('manager_id', $managerId)
        ->where('status', 4)
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
                'ready_production' => $group->sum('ready_production'),
                'total_length'     => $first->total_length,
                'status'           => $first->status,
            ];
        })
        ->values();

    // DAILY

    $daily = Production::where('employee_id', $employee->id)
        ->where('factory_id', $factoryId)
        ->where('manager_id', $managerId)
        ->where('status', 4)
        ->whereDate('created_at', today())
        ->sum('ready_production');

    // WEEKLY

    $weekly = Production::where('employee_id', $employee->id)
        ->where('factory_id', $factoryId)
        ->where('manager_id', $managerId)
        ->where('status', 4)
        ->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])
        ->sum('ready_production');

    // MONTHLY

    $monthly = Production::where('employee_id', $employee->id)
        ->where('factory_id', $factoryId)
        ->where('manager_id', $managerId)
        ->where('status', 4)
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('ready_production');

    return response()->json([
        'factory_id' => $factoryId,
        'manager_id' => $managerId,

        'pending' => $pending,

        'completed' => $completed,

        'daily' => $daily,

        'weekly' => $weekly,

        'monthly' => $monthly,
    ]);
}
}