<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Production;
use App\Models\Attendence;
use App\Models\User;
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
    $request->validate([
        'machine_id' => 'required',
        'employee_id' => 'required',
        'factory_id' => 'required',
        'variety_type' => 'required',
        'total_length' => 'required',
        'ready_production' => 'required',
    ]);

    $production = Production::create([
        'machine_id' => $request->machine_id,
        'employee_id' => $request->employee_id,
        'factory_id' => $request->factory_id,
        'variety_type' => $request->variety_type,
        'total_length' => $request->total_length,
        'ready_production' => $request->ready_production,
        'shift_start' => $request->shift_start,
        'shift_end' => $request->shift_end,
        'status' => 1, // Pending
    ]);
    // Attendance Auto Mark
Attendence::create([
    'employee_id' => $request->employee_id,
    'type' => 'present',
    'timestamp' => now(),
    'production_id' => $production->id,
]);

    return response()->json([
        'message' => 'Production submitted successfully',
        'data' => $production
    ], 201);
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

//assign production batch to machine
public function assignProduction(Request $request)
{
    $request->validate([
        'machine_id'   => 'required|integer',
        'variety_type' => 'required|string',
        'total_length' => 'required|numeric',
    ]);

    // copy the data from the last production of the machine
    $last = Production::where('machine_id', $request->machine_id)
        ->latest()
        ->first();

    //  Unique batch_id generate 
    $batchId = 'BATCH-' . $request->machine_id . '-' . time();

    Production::create([
        'batch_id'         => $batchId,
        'machine_id'       => $request->machine_id,
        'employee_id'      => $last?->employee_id,
        'factory_id'       => $last?->factory_id ?? 1,
        'manager_id'       => $last?->manager_id,
        'variety_type'     => $request->variety_type,
        'total_length'     => $request->total_length,
        'ready_production' => 0,
        'shift_start'      => $last?->shift_start,
        'shift_end'        => $last?->shift_end,
        'status'           => 1,
    ]);

    return response()->json([
        'message'  => 'Production assigned successfully',
        'batch_id' => $batchId,
    ], 201);
}

}