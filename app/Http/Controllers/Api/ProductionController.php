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
        $productions = Production::with(['factory', 'employee.user', 'machine'])->get();

        return response()->json($productions, 200);
    }

    // 2. Add production employee
   public function store(Request $request)
{
    \Log::info('STORE HIT');

    $request->validate([
        'machine_id' => 'required|integer',
        'user_id' => 'required|integer', // frontend se AuthService.userId aayega
        'factory_id' => 'required|integer',
        'variety_type' => 'required|string',
        'total_length' => 'required|numeric',
        'ready_production' => 'required|numeric',
        'waste_production' => 'required|numeric',
    ]);

    $factory = Factory::find($request->factory_id);
    if (!$factory) {
        return response()->json(['message' => 'Factory not found'], 404);
    }

    // ✅ user_id se employees table ka record dhoondo
    $employee = Employee::where('user_id', $request->user_id)
        ->where('factory_id', $request->factory_id)
        ->first();

    if (!$employee) {
        return response()->json(['message' => 'Employee not found for this user/factory'], 404);
    }
       $lastProduction = Production::where('machine_id', $request->machine_id)
    ->latest()
    ->first();

$batchId = $lastProduction?->batch_id;

// ✅ manager_id ab poori factory se dhoondo 
$managerId = Production::where('factory_id', $request->factory_id)
    ->whereNotNull('manager_id')
    ->latest()
    ->value('manager_id');

        $previousRemaining = $lastProduction?->remaining ?? $request->total_length;

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
            'employee_id' => $employee->id, // ✅ employees.id

            'factory_id' => $request->factory_id,
            'manager_id' => $managerId,

            'variety_type' => $request->variety_type,
            'total_length' => $request->total_length,

            'ready_production' => $request->ready_production,
            'waste_production' => $request->waste_production,

            'remaining' => $newRemaining,

            'batch_id' => $batchId,

            'shift_start' => $employee->shift_starttime,
            'shift_end' => $employee->shift_endtime,

            'status' => 1,
        ]);

        return response()->json([
            'message' => 'Production submitted successfully',
            'data' => $production
        ]);
    }

    // 3. Single production
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
        ]);
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
        ]);
    }

    // 9. Assign production (FIXED)
    public function assignProduction(Request $request)
    {
        $request->validate([
            'machine_id' => 'required|integer',
            'employee_id' => 'required|integer',
            'variety_type' => 'required|string',
            'total_length' => 'required|numeric',
        ]);

        $employee = Employee::find($request->employee_id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $last = Production::where('machine_id', $request->machine_id)
            ->latest()
            ->first();

        $batchId = 'BATCH-' . $request->machine_id . '-' . time();

        Production::create([
            'batch_id' => $batchId,
            'machine_id' => $request->machine_id,

            'employee_id' => $employee->id, // ✅ correct

            'factory_id' => $last?->factory_id ?? 1,
            'manager_id' => $last?->manager_id,

            'variety_type' => $request->variety_type,
            'total_length' => $request->total_length,

            'ready_production' => 0,
            'remaining' => $request->total_length,

            'shift_start' => $employee->shift_starttime,
            'shift_end' => $employee->shift_endtime,

            'status' => 1,
        ]);

        return response()->json([
            'message' => 'Production assigned successfully',
            'batch_id' => $batchId,
        ], 201);
    }
}