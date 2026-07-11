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

        // ✅ Machine ka CURRENT/latest batch dhoondo (batch ab machine ka hai, kisi ek employee ka nahi)
        $latestRow = Production::where('machine_id', $id)
            ->whereNotNull('batch_id')
            ->latest()
            ->first();

        if (!$latestRow) {
            return response()->json([
                'machine_id'   => $machine->id,
                'machine_name' => $machine->machine_name,
                'machine_type' => $machine->machine_type,
                'batch_id'     => null,
                'variety_type' => null,
                'total_length' => 0,
                'ready_production' => 0,
                'waste_production' => 0,
                'remaining'    => 0,
                'shifts'       => [],
                'message'      => 'No production batch assigned yet',
            ]);
        }

        $batchId     = $latestRow->batch_id;
        $totalLength = $latestRow->total_length;
        $varietyType = $latestRow->variety_type;

        $readyTotal = Production::where('machine_id', $id)
            ->where('batch_id', $batchId)
            ->sum('ready_production');

        $wasteTotal = Production::where('machine_id', $id)
            ->where('batch_id', $batchId)
            ->sum('waste_production');

        $remaining = max(0, $totalLength - ($readyTotal + $wasteTotal));

        $employeeIds = Production::where('machine_id', $id)
            ->where('batch_id', $batchId)
            ->whereNotNull('employee_id')
            ->distinct()
            ->pluck('employee_id');

        $shifts = [];

        foreach ($employeeIds as $empId) {

            $empLatest = Production::with(['employeedetails.user'])
                ->where('machine_id', $id)
                ->where('batch_id', $batchId)
                ->where('employee_id', $empId)
                ->latest()
                ->first();

            if (!$empLatest) {
                continue;
            }

            $empReady = Production::where('machine_id', $id)
                ->where('batch_id', $batchId)
                ->where('employee_id', $empId)
                ->sum('ready_production');

            $empWaste = Production::where('machine_id', $id)
                ->where('batch_id', $batchId)
                ->where('employee_id', $empId)
                ->sum('waste_production');

            $shifts[] = [
                'employee_id'      => $empLatest->employee_id,
                'user_id'          => optional($empLatest->employeedetails)->user_id,
                'employee_name'    => optional($empLatest->employeedetails)->user->name ?? '',
                'shift_start'      => $empLatest->employeedetails?->shift_starttime,
                'shift_end'        => $empLatest->employeedetails?->shift_endtime,
                'ready_production' => $empReady,
                'waste_production' => $empWaste,
                'status'           => $empLatest->status,
            ];
        }

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

            "batch_id"         => $batchId,
            "variety_type"     => $varietyType,
            "total_length"     => $totalLength,
            "ready_production" => $readyTotal,
            "waste_production" => $wasteTotal,
            "remaining"        => $remaining,

            "shifts" => $shifts,

            "weekly_production"  => $weeklyProduction,
            "yearly_production"  => $yearlyProduction,
            "daily_production"   => $dailyProduction,

        ]);
    }
}