<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Production;
use App\Models\Attendence;
use App\Models\User;
use App\Models\Employee;
use App\Models\Factory;
class ProductionController extends Controller
{
    // 1. Show all productions
public function index() 
{
    // Eager loading multiple relationships:
    // 1. factory (Factory name ke liye)
    // 2. employee.user (User name ke liye)
    // 3. machine (Machine ID/Type ke liye)
    $productions = Production::with(['factory', 'employee.user', 'machine'])->get();

    return response()->json($productions, 200);
}
    // public function index()
    // {
    //     return response()->json(Production::all(), 200);
    // }

    // 2. Add production
 public function store(Request $request)
{
    \Log::info('STORE HIT');

    $request->validate([
        'machine_id' => 'required',
        'employee_id' => 'required',
        'factory_id' => 'required',
        'variety_type' => 'required',
        'total_length' => 'required',
        'ready_production' => 'required',
        'waste_production' => 'required',
    ]);

    $factory = Factory::find($request->factory_id);

    if (!$factory) {
        return response()->json(["message" => "Factory not found"], 404);
    }

    $employee = Employee::where('user_id', $request->employee_id)->first();

<<<<<<< HEAD
    $batchId = $lastProduction?->batch_id;
    $managerId = $lastProduction?->manager_id;
    $shiftStart = $lastProduction?->shift_start;
    $shiftEnd   = $lastProduction?->shift_end;

   //access employee id based on user id 
    $employee = Employee::where('user_id',$request->employee_id)
        ->first();

    if(!$employee){
        return response()->json([
            "message"=>"Employee record not found"
        ],404);
=======
    if (!$employee) {
        return response()->json(["message" => "Employee not found"], 404);
>>>>>>> fe9b0c82921ef33ac30ff2a5a2b5a7d0a5c19091
    }

    // 🔥 LAST PRODUCTION GET
   // 🔥 LAST PRODUCTION GET
$lastProduction = Production::where('machine_id', $request->machine_id)
    ->whereNotNull('batch_id')
    ->latest()
    ->first();

$batchId = $lastProduction?->batch_id;
$shiftStart = $lastProduction?->shift_start;
$shiftEnd = $lastProduction?->shift_end;

// ✅ Manager ID previous production se copy hogi
$managerId = $lastProduction?->manager_id;

// 🔥 OLD REMAINING
$previousRemaining = $lastProduction?->remaining ?? $request->total_length;

// ✅ Ready + Waste dono minus hongay
$newRemaining =
    $previousRemaining
    - $request->ready_production
    - $request->waste_production;

if ($newRemaining < 0) {
    return response()->json([
        "message" => "Production limit exceeded"
    ], 400);
}

$production = Production::create([
    'machine_id' => $request->machine_id,
    'employee_id' => $employee->id,
    'factory_id' => $request->factory_id,
    'manager_id' => $managerId,

    'variety_type' => $request->variety_type,
    'total_length' => $request->total_length,

    'ready_production' => $request->ready_production,
    'waste_production' => $request->waste_production,

    // ✅ ready + waste deduct karke save
    'remaining' => $newRemaining,

    'batch_id' => $batchId,

    'shift_start' => $shiftStart ?? now(),
    'shift_end' => $shiftEnd,

    'status' => 1,
]);

    return response()->json([
        'message' => 'Production submitted successfully',
        'data' => $production
    ]);
}

    // 3. Show single production
    public function edit($id)
    {
        $production = Production::find($id);

        if (!$production) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($production, 200);
    }

    // 4. Update production
    public function update(Request $request, $id)
    {
        $production = Production::find($id);

        if (!$production) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $production->update($request->all());

        return response()->json([
            'message' => 'Production updated successfully',
            'data' => $production
        ], 200);
    }

    // 5. Delete production
    public function destroy($id)
    {
        $production = Production::find($id);

        if (!$production) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $production->delete();

        return response()->json([
            'message' => 'Production deleted successfully'
        ], 200);
    }

    //production according status
public function pending()
{
    $productions = Production::with([
        'machine',
        'employee.user'
    ])
    ->where('status', 1)
    ->latest()
    ->get();

    return response()->json(
        $productions,
        200
    );
}

public function approve($id)
{
    $production =
        Production::findOrFail($id);

    $production->status = 2;

    $production->save();

    return response()->json([
        'message' =>
            'Production Approved'
    ]);
}

public function reject($id)
{
    $production =
        Production::findOrFail($id);

    $production->status = 3;

    $production->save();

    return response()->json([
        'message' =>
            'Production Rejected'
    ]);
}
// 6. Assign production to employee using unique batch_id

public function assignProduction(Request $request) { $request->validate([ 
    'machine_id' => 'required|integer', 
    'variety_type' => 'required|string', 
    'total_length' => 'required|numeric', ]);
 // copy the data from the last production of the machine 
 $last = Production::where('machine_id', $request->machine_id) ->latest() ->first();
 //Unique batch_id generate
  $batchId = 'BATCH-' . $request->machine_id . '-' . time();
  $employee = Employee::find($request->employee_id); 
  Production::create([ 'batch_id' => $batchId, 'machine_id' => $request->machine_id,
   'employee_id' => $last?->employee_id, 
   'factory_id' => $last?->factory_id ?? 1, 
   'manager_id' => $last?->manager_id, 
   'variety_type' => $request->variety_type, 
   'total_length' => $request->total_length, 
   'ready_production' => 0, 
   'shift_start' => $employee?->shift_starttime, 
   'shift_end' => $employee?->shift_endtime, 'status' => 1, ]);
    return response()->json([ 'message' => 'Production assigned successfully',
     'batch_id' => $batchId, ], 201); }
}