<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Production;
use App\Models\Attendence;
use App\Models\User;
use App\Models\Employee;
use App\Models\Factory;
use App\Models\Machine;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class ProductionController extends Controller
{
    // Show all productions
    public function index()
    {
        $productions = Production::with(['factory', 'employee.user', 'machine'])->get();

        return response()->json($productions, 200);
    }

    // Add production employee
   public function store(Request $request)
{
    \Log::info('STORE HIT');
    $request->validate([
        'machine_id' => 'required|integer',
        'user_id' => 'required|integer', // frontend se AuthService.userId aayega
        'factory_id' => 'required|integer',
        'ready_production' => 'required|numeric',
        'waste_production' => 'required|numeric',
    ]);

    $factory = Factory::find($request->factory_id);
    if (!$factory) {
        return response()->json(['message' => 'Factory not found'], 404);
    }

    // find employee by user_id in employees table 
    $employee = Employee::where('user_id', $request->user_id)
        ->where('factory_id', $request->factory_id)
        ->first();

    if (!$employee) {
        return response()->json(['message' => 'Employee not found for this user/factory'], 404);
    }

    // employee only allowed to submit production during their shift time
    $isSelfSubmission = $request->user() && $request->user()->id == $request->user_id;

    if ($isSelfSubmission && !$this->isWithinShift($employee->shift_starttime, $employee->shift_endtime)) {
        return response()->json([
            'message' => "You can only submit production during your shift ({$employee->shift_starttime} - {$employee->shift_endtime})",
        ], 403);
    }
// Latest production record for this employee on this machine
    $ownLatest = Production::where('machine_id', $request->machine_id)
        ->where('employee_id', $employee->id)
        ->latest()
        ->first();

    if (!$ownLatest || !$ownLatest->batch_id) {
        return response()->json([
            'message' => 'No production batch assigned to this machine yet. Ask owner to assign a batch first.',
        ], 422);
    }

    $batchId     = $ownLatest->batch_id;
    $varietyType = $ownLatest->variety_type;
    $totalLength = $ownLatest->total_length;
     


    // find latest manager_id for this factory (if any)
    $managerId = Production::where('factory_id', $request->factory_id)
        ->whereNotNull('manager_id')
        ->latest()
        ->value('manager_id');

    // remaining production check minus both employees record ready production
    $readySoFar = Production::where('machine_id', $request->machine_id)
        ->where('batch_id', $batchId)
        ->sum('ready_production');

    $wasteSoFar = Production::where('machine_id', $request->machine_id)
        ->where('batch_id', $batchId)
        ->sum('waste_production');

    $previousRemaining = max(0, $totalLength - ($readySoFar + $wasteSoFar));

    $newRemaining =
        $previousRemaining
        - $request->ready_production
        - $request->waste_production;

    if ($newRemaining < 0) {
        return response()->json([
            "message" => "Production limit exceeded — sirf $previousRemaining hi remaining hai (dono shifts mila kar)"
        ], 400);
    }

        $production = Production::create([
            'machine_id' => $request->machine_id,
            'employee_id' => $employee->id, 

            'factory_id' => $request->factory_id,
            'manager_id' => $managerId,

            'variety_type' => $varietyType,
            'total_length' => $totalLength,

            'ready_production' => $request->ready_production,
            'waste_production' => $request->waste_production,

            'remaining' => $newRemaining,

            'batch_id' => $batchId,

            'shift_start' => $employee->shift_starttime,
            'shift_end' => $employee->shift_endtime,

            'status' => 1,
        ]);

        // send notification to all owners about new production submission
        try {
            $machineName = Machine::where('id', $request->machine_id)->value('machine_name');
            $employeeName = $employee->user?->name ?? 'Employee';

            $owners = User::role('owner')->get();
            foreach ($owners as $owner) {
                Notification::create([
                    'user_id'       => $owner->id,
                    'production_id' => $production->id,
                    'sender_id'     => $request->user_id,
                    'title'         => 'New Production Submitted',
                    'message'       => "$employeeName submitted production on machine \"$machineName\" — pending approval",
                    'type'          => 'production_created',
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Owner notification create failed: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Production submitted successfully',
            'data' => $production
        ]);
    }

    // Single production
    public function edit($id)
    {
        $production = Production::find($id);

        if (!$production) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($production, 200);
    }

    // Update production
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

    // Delete production
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
 
    // View payments for a factory (grouped by batch_id)
    
    public function viewPayments($factoryId)
    {
        $batches = Production::where('factory_id', $factoryId)
            ->whereNotNull('batch_id')
            ->selectRaw('batch_id, MAX(total_length) as total_length, MAX(amount_per_meter) as amount_per_meter')
            ->groupBy('batch_id')
            ->get()
            ->map(function ($row) {
                return [
                    'batch_id'         => $row->batch_id,
                    'total_length'     => (float) $row->total_length,
                    'amount_per_meter' => (float) $row->amount_per_meter,
                ];
            });

        return response()->json([
            'data' => $batches,
        ]);
    }
    // Assign production
    public function assignProduction(Request $request)
    {
        $request->validate([
            'machine_id' => 'required|integer',
            'variety_type' => 'required|string',
            'total_length' => 'required|numeric',
            'amount_per_meter' => 'required|numeric',
        ]);

        // assign batch to all employes that work on this machine 
        $employeeIds = Production::where('machine_id', $request->machine_id)
            ->whereNotNull('employee_id')
            ->distinct()
            ->pluck('employee_id');

        if ($employeeIds->isEmpty()) {
            return response()->json([
                'message' => 'First assign employees to this machine, then assign production batch.',
            ], 422);
        }

        $batchId = 'BATCH-' . $request->machine_id . '-' . time();

        $created = [];
        info($request);

        foreach ($employeeIds as $empId) {
            $employee = Employee::find($empId);
            if (!$employee) continue;

            $last = Production::where('machine_id', $request->machine_id)
                ->where('employee_id', $empId)
                ->latest()
                ->first();

            $production = Production::create([
                'batch_id' => $batchId,
                'machine_id' => $request->machine_id,
                'employee_id' => $employee->id,

                'factory_id' => $last?->factory_id,
                'manager_id' => $last?->manager_id,

                'variety_type' => $request->variety_type,
                'total_length' => $request->total_length,
                'amount_per_meter' => $request->amount_per_meter,

                'ready_production' => 0,
                'waste_production' => 0,
                'remaining' => $request->total_length,

                'shift_start' => $employee->shift_starttime,
                'shift_end' => $employee->shift_endtime,

                'status' => 1,
            ]);

            $created[] = $production;   // append to the array for count() later

             $user_Name = User::whereId($employee->user_id)->first();
             Notification::create([
                    'user_id'       => $employee->user_id,
                    'production_id' => $production->id,
                    'sender_id'     => Auth::user()->id,
                    'title'         => 'New Production assigned',
                    'message'       => "$user_Name->name assigned production by ".Auth::user()->name,
                    'type'          => 'production_assigned',
                ]);
        }

        return response()->json([
            'message' => 'Production batch assigned to ' . count($created) . ' employee(s) successfully',
            'batch_id' => $batchId,
        ], 201);
    }

    // check the employee shift of current time
    private function isWithinShift($start, $end)
    {
        if (!$start || !$end) {
            return true;
        }

        $now   = \Carbon\Carbon::now()->format('H:i:s');
        $start = \Carbon\Carbon::parse($start)->format('H:i:s');
        $end   = \Carbon\Carbon::parse($end)->format('H:i:s');

        if ($start <= $end) {
            // Normal shift 
            return $now >= $start && $now <= $end;
        }

        // Overnight shift 
        return $now >= $start || $now <= $end;
    }
}