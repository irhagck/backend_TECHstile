<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine;
use App\Models\User;
use App\Models\Employee;
use App\Models\Factory;
use App\Models\Production;

class MachineController extends Controller
{
    // 🔹 Show all machines
    public function index($factoryId)
{
    $machines = Machine::where(
        'factory_id',
        $factoryId
    )->get();

    return response()->json([
        'status' => true,
        'data' => $machines
    ]);
}

    // 🔹 Add machine
    public function store(Request $request)
    {
        $request->validate([
            'machine_id' => 'required',
            'machine_type' => 'required',
            'time' => 'required'
        ]);

        $machine = Machine::create([
         'machine_id' => $request->machine_id,
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
            'machine_id' => $request->machine_id,
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
    // 🔹 Machine details
   public function details($id)
{
    $machine = Machine::find($id);

    if (!$machine) {
        return response()->json([
            'status' => false,
            'message' => 'Machine not found'
        ], 404);
    }

   $production = Production::where(
    'machine_id',
    $id
)->latest()->first();

$employeeName = null;
$factoryName = null;

if ($production) {

    $employee = Employee::find(
        $production->employee_id
    );

    if ($employee) {

        $user = User::find(
            $employee->user_id
        );

        $employeeName = $user?->name;
    }

    $factory = Factory::find(
        $production->factory_id
    );

    $factoryName = $factory?->name;
}
    return response()->json([
        'status' => true,

        'machine' => $machine,

        'employee_name' =>
            $employeeName,

        'factory_name' =>
            $factoryName,

        'variety' =>
            $production?->variety_type,

        'ready_production' =>
            $production?->ready_production,

        'assign_date' =>
            $production?->shift_start,

        'machine_status' =>
            $machine->machine_status,

        'total_production' =>
            $production?->total_length,
    ]);
}
}