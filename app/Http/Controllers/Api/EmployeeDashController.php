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
 public function dashboard(Request $request, $id)
{
    $employee = Employee::where('user_id', $id)->first();

    if (!$employee) {
        return response()->json(['message' => 'Employee not found'], 404);
    }

    $productions = Production::with([
        'machineemploye',
        'employeedetails.user'
    ])
    ->where('employee_id', $employee->id)
    ->where('status', 2)
    ->get();

    // 🔥 GROUP BY MACHINE + VARIETY + BATCH (IMPORTANT)
    $grouped = $productions->groupBy(function ($item) {
        return $item->machine_id . '_' . $item->variety_type . '_' . $item->batch_id;
    });

    $data = $grouped->map(function ($group) {

        $first = $group->first();

        // ✅ READY PRODUCTION = SUM
        $readyProduction = $group->sum('ready_production');

        // ❗ TOTAL LENGTH = TAKE SINGLE (NOT SUM)
        $totalLength = $first->total_length;

        $progress = ($totalLength > 0)
            ? round(($readyProduction / $totalLength) * 100, 2)
            : 0;

        return [
            'machine_id' => $first->machine_id,
            'machine_type' => $first->machineemploye->machine_type ?? '',
            'machine_status' => $first->machineemploye->status ?? '',

            'employee_name' => $first->employeedetails?->user?->name ?? '',
            'variety_type' => $first->variety_type ?? '',
            'batch_id' => $first->batch_id ?? '',

            // 🔥 IMPORTANT FIX
            'ready_production' => $readyProduction,
            'total_length' => $totalLength,

            'progress' => $progress,
        ];
    })->values();

    return response()->json([
        'employee_name' => $employee->user->name ?? '',
        'total_machines' => $data->count(),
        'total_production' => $data->sum('total_length'),
        'total_ready_production' => $data->sum('ready_production'),
        'machines' => $data,
    ]);
}

    // /profile method for employee
public function profile(Request $request, $id)
{
    $user = User::with('roles')->find($id);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404);
    }

    $employee = Employee::where('user_id', $id)->first();

    $totalMachines = 0;
    $totalProduction = 0;
    $totalReadyProduction = 0;
    $attendanceCount = 0;

   if ($employee) {

    // 🔥 ONLY APPROVED PRODUCTIONS
    $productions = Production::where('employee_id', $employee->id)
        ->where('status', 2)
        ->get();

    // 🔥 GROUP (same machine + batch + variety)
    $grouped = $productions->groupBy(function ($item) {
        return $item->machine_id . '_' . $item->variety_type . '_' . $item->batch_id;
    });

    $totalMachines = $grouped->count();

    $totalProduction = $grouped->sum(function ($group) {
        return $group->first()->total_length;
    });

    $totalReadyProduction = $grouped->sum(function ($group) {
        return $group->sum('ready_production');
    });

    $attendanceCount = Attendence::where(
        'employee_id',
        $employee->id
    )->count();
}

   return response()->json([
    'success' => true,
    'data' => [
        ...$user->toArray(),

        'total_machines' => $totalMachines,
        'total_production' => $totalProduction,
        'total_ready_production' => $totalReadyProduction,
        'attendance_count' => $attendanceCount,
    ]
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

    // ✅ Sirf isi batch ka sum
    $totalReadyProduction = Production::where('machine_id', $id)
        ->where('employee_id', $employee->id)
        ->where('batch_id', $production->batch_id)
        ->sum('ready_production');

    $remaining = max(0, $production->total_length - $totalReadyProduction);
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

    $attendanceCount = Attendence::where('employee_id', $employee->id)->count();

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
        'attendance_count'   => $attendanceCount,
    ]);
}

// employee history functions
     public function employeeHistory($id)
{
    $employee = Employee::where('user_id',$id)->first();

    if(!$employee){
        return response()->json([
            'message'=>'Employee not found'
        ],404);
    }


    // ======================
    // PENDING
    // ======================

    $pending = Production::with([
        'machine'
    ])
    ->where('employee_id',$employee->id)
    ->where('status',1)
    ->latest()
    ->get();


    // ======================
    // APPROVED
    // ======================

    $approved = Production::with([
        'machine'
    ])
    ->where('employee_id',$employee->id)
    ->where('status',2)
    ->get();


    $completed = $approved
    ->groupBy(function($item){

        return $item->machine_id.'_'
        .$item->variety_type.'_'
        .$item->batch_id;

    })
    ->map(function($group){

        $first = $group->first();


        return [

            'machine_id'=>$first->machine_id,

            'machine_type'=>
            $first->machine?->machine_type,


            'variety_type'=>
            $first->variety_type,


            // SUM READY
            'ready_production'=>
            $group->sum('ready_production'),


            // SAME TOTAL
            'total_length'=>
            $first->total_length,


            'status'=>$first->status,

        ];

    })
    ->values();



    // ======================
    // TOTALS
    // ======================


    $daily =
    Production::where('employee_id',$employee->id)
    ->where('status',2)
    ->whereDate(
        'created_at',
        today()
    )
    ->sum('ready_production');



    $weekly =
    Production::where('employee_id',$employee->id)
    ->where('status',2)
    ->whereBetween(
        'created_at',
        [
          now()->startOfWeek(),
          now()->endOfWeek()
        ]
    )
    ->sum('ready_production');



    $monthly =
    Production::where('employee_id',$employee->id)
    ->where('status',2)
    ->whereMonth(
        'created_at',
        now()->month
    )
    ->sum('ready_production');




    return response()->json([

        'pending'=>$pending,

        'completed'=>$completed,

        'daily'=>$daily,

        'weekly'=>$weekly,

        'monthly'=>$monthly,

    ]);

}
}