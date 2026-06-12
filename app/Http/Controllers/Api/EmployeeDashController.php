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
        // Logged-in user
       $employee = Employee::where('user_id', $id)->first();

    if (!$employee) {
        return response()->json([
            'message' => 'Employee not found',
            'user_id' => $id  // debug ke liye
        ], 404);
        }

        $productions = Production::with([
            'machineemploye',
            'employeedetails.user'
        ])
        ->where('employee_id', $employee->id)
        ->get();

        $data = $productions->map(function ($production) {

            $progress = 0;

            if (
                $production->total_length > 0 &&
                $production->ready_production != null
            ) {
                $progress = round(
                    ($production->ready_production / $production->total_length) * 100,
                    2
                );
            }

            return [
                'machine_id' => $production->machineemploye?->id,
                'machine_type' => $production->machineemploye?->machine_type,
                'machine_status' => $production->machineemploye?->status,

                'employee_name' => $production->employeedetails?->user?->name,

                'variety_type' => $production->variety_type,
                'ready_production' => $production->ready_production,
                'total_length' => $production->total_length,

                'progress' => $progress,
            ];
        });

      return response()->json([
    'employee_name' => $employee->user->name ?? '',
    'total_machines' => $productions->count(),
    'total_production' => $productions->sum('total_length'),
    'total_ready_production' => $productions->sum('ready_production'),
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
        $productions = Production::where(
            'employee_id',
            $employee->id
        )->get();

        $totalMachines = $productions->count();

        $totalProduction = $productions->sum(
            'total_length'
        );

        $totalReadyProduction = $productions->sum(
            'ready_production'
        );
        

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
   public function machineDetails(Request $request, $id)
{
    $user = $request->user(); // ✅ logged in user

    // ✅ $employee define karo — yeh missing tha
    $employee = Employee::where('user_id', $user->id)->first();

    if (!$employee) {
        return response()->json([
            'message' => 'Employee not found'
        ], 404);
    }

    $machine = Machine::find($id);

    if (!$machine) {
        return response()->json([
            'message' => 'Machine not found'
        ], 404);
    }

    $production = Production::with([
        'employeedetails.user'
    ])
    ->where('machine_id', $id)
    ->latest()
    ->first();

    if (!$production) {
        return response()->json([
            'message' => 'No production found'
        ], 404);
    }
    
 $dailyProduction = Production::where(
    'machine_id',
    $id
)
->whereDate(
    'created_at',
    Carbon::today()
)
->sum('ready_production');

$weeklyProduction = Production::where(
    'machine_id',
    $id
)
->whereBetween(
    'created_at',
    [
        Carbon::now()->startOfWeek(),
        Carbon::now()->endOfWeek()
    ]
)
->sum('ready_production');

$yearlyProduction = Production::where(
    'machine_id',
    $id
)
->whereYear(
    'created_at',
    Carbon::now()->year
)
->sum('ready_production');
$employeeId = $production->employee_id;
 $attendanceCount = Attendence::where(
        'employee_id',$employee->id)->count();

   return response()->json([
    'machine_id' => $machine->id,
    'machine_type' => $machine->machine_type,
    'status' => $machine->status,

    'employee_id' =>
        $production->employeedetails?->employee_id,

    'employee_name' =>
        $production->employeedetails?->user?->name,

    'shift_start' =>
        $production->shift_start,

    'shift_end' =>
        $production->shift_end,

    'variety_type' =>
        $production->variety_type,

    'total_length' =>
        $production->total_length,

    'ready_production' =>
        $production->ready_production,

    'daily_production' =>
        $dailyProduction,

    'weekly_production' =>
        $weeklyProduction,

    'yearly_production' =>
        $yearlyProduction,
     'attendance_count' =>
         $attendanceCount,
]);
}
}