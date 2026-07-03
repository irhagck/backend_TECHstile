<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine;
use App\Models\User;
use App\Models\Employee;
use App\Models\Factory;
use App\Models\Production;
use Carbon\Carbon;
class MachineController extends Controller
{
    // 🔹 Show all machines
    public function index($factoryId)
{
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
        'status' => true,
        'data' => $machines,
        'total_machines'  => $machines->count(),
        'active_machines' => $activeMachineIds->count(),
    ]);
}

    // 🔹 Add machine
    public function store(Request $request)
    {
        $request->validate([
            'machine_name' => 'required',
            'machine_type' => 'required',
            'time' => 'required'
        ]);

        $machine = Machine::create([
            'machine_name' => $request->machine_name,
            'machine_type' => $request->machine_type,
            'time' => $request->time,
            'factory_id' => $request->factory_id,
        ]);


        return response()->json([
            'status' => true,
            'message' => 'Machine created successfully',
            'data' => $machine
        ]);
    }

    // 🔹 Get single machine (for edit)
    public function edit($id)
    {
        $machine = Machine::find($id);

        if (!$machine) {
            return response()->json([
                'status' => false,
                'message' => 'Machine not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $machine
        ]);
    }

    // 🔹 Update machine
    public function update(Request $request, $id)
    {
        $machine = Machine::find($id);

        if (!$machine) {
            return response()->json([
                'status' => false,
                'message' => 'Machine not found'
            ], 404);
        }

        $machine->update([
            'machine_name' => $request->machine_name,
            'machine_type' => $request->machine_type,
            'time' => $request->time,
            'factory_id' => $request->factory_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Machine updated successfully',
            'data' => $machine
        ]);
    }

    // 🔹 Delete machine
    public function destroy($id)
    {
        $machine = Machine::find($id);

        if (!$machine) {
            return response()->json([
                'status' => false,
                'message' => 'Machine not found'
            ], 404);
        }

        $machine->delete();

        return response()->json([
            'status' => true,
            'message' => 'Machine deleted successfully'
        ]);
    }
   public function details($id)
{
    $machine = Machine::find($id);

    if(!$machine){
        return response()->json([
            'message'=>'Machine not found'
        ],404);
    }

    // ✅ Is machine par ab tak jitne employees assign ho chuke hain (dono shifts), un sabko nikalo
    $employeeIds = Production::where('machine_id', $id)
        ->whereNotNull('employee_id')
        ->distinct()
        ->pluck('employee_id');

    if ($employeeIds->isEmpty()) {
        return response()->json([
            'machine_id'    => $machine->id,
            'machine_name'  => $machine->machine_name,
            'machine_type'  => $machine->machine_type,
            'shifts'        => [],
            'message'       => 'No production found',
        ]);
    }

    $shifts = [];

    foreach ($employeeIds as $empId) {

        // Us employee ki is machine par sab se latest assignment/batch
        $latest = Production::with(['employeedetails.user'])
            ->where('machine_id', $id)
            ->where('employee_id', $empId)
            ->latest()
            ->first();

        if (!$latest) {
            continue;
        }

        // Usi batch ki ready/waste sum (sirf usi employee/batch ki)
        $readyProduction = Production::where('machine_id', $id)
            ->where('employee_id', $empId)
            ->where('batch_id', $latest->batch_id)
            ->sum('ready_production');

        $wasteProduction = Production::where('machine_id', $id)
            ->where('employee_id', $empId)
            ->where('batch_id', $latest->batch_id)
            ->sum('waste_production');

        $remaining = max(
            0,
            $latest->total_length - ($readyProduction + $wasteProduction)
        );

        $shifts[] = [
            'employee_id'      => $latest->employee_id,
            'user_id'          => optional($latest->employeedetails)->user_id,
            'employee_name'    => optional($latest->employeedetails)->user->name ?? '',
            'shift_start'      => $latest->employeedetails?->shift_starttime,
            'shift_end'        => $latest->employeedetails?->shift_endtime,
            'batch_id'         => $latest->batch_id,
            'variety_type'     => $latest->variety_type,
            'total_length'     => $latest->total_length,
            'ready_production' => $readyProduction,
            'waste_production' => $wasteProduction,
            'remaining'        => $remaining,
            'status'           => $latest->status,
        ];
    }

    // ✅ Sort: din ki shift (08:00) pehle, raat ki shift (20:00) baad me
    usort($shifts, function ($a, $b) {
        return strcmp((string) $a['shift_start'], (string) $b['shift_start']);
    });

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

    return response()->json([

        "machine_id"   => $machine->id,
        "machine_name" => $machine->machine_name,
        "machine_type" => $machine->machine_type,

        // ✅ Ab dono shifts ke employees is array me aate hain
        "shifts" => $shifts,

        "weekly_production"  => $weeklyProduction,
        "yearly_production"  => $yearlyProduction,
        "daily_production"   => $dailyProduction,

    ]);
}
}