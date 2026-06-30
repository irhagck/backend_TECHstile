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

    return response()->json([
        'status' => true,
        'data' => $machines
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


    $production = Production::with([
        'employeedetails.user'
    ])
    ->where('machine_id',$id)
    ->latest()
    ->first();


    if(!$production){

        return response()->json([
            'machine_id'=>$machine->id,
            'machine_type'=>$machine->machine_type,
            'machine_status'=>$machine->status,
            'message'=>'No production found'
        ]);

    }



    // same batch ready sum

    // Same batch ready production sum
$readyProduction = Production::where('machine_id', $id)
    ->where('batch_id', $production->batch_id)
    ->sum('ready_production');

// Same batch waste production sum
$wasteProduction = Production::where('machine_id', $id)
    ->where('batch_id', $production->batch_id)
    ->sum('waste_production');

// Remaining = Total - Ready - Waste
$remaining = max(
    0,
    $production->total_length - ($readyProduction + $wasteProduction)
);



    // only daily

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


        "machine_id"=>$machine->id,

        "machine_type"=>$machine->machine_type,

        "machine_status"=>$machine->status,


        "employee_id"=>$production->employee_id,


        "employee_name" =>
        optional($production->employeedetails)
        ->user
        ->name ?? "",


        "shift_start" =>
        $production->employeedetails?->shift_starttime,


        "shift_end" =>
        $production->employeedetails?->shift_endtime,



        "variety_type"=>$production->variety_type,


        "total_length"=>$production->total_length,


        "ready_production"=>$readyProduction,
         "waste_production" => $wasteProduction,

        "remaining"=>$remaining,
        "weekly_production"  => $weeklyProduction,
        "yearly_production" => $yearlyProduction,

        "daily_production"=>$dailyProduction,


    ]);
}
}