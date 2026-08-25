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

    // ✅ Current time employee ki shift window ke andar hai ya nahi (overnight shifts bhi handle karta hai)
    // e.g. day shift 08:00-20:00, night shift 20:00-08:00 (agla din)

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

    // ✅ user_id se employees table ka record dhoondo
    $employee = Employee::where('user_id', $request->user_id)
        ->where('factory_id', $request->factory_id)
        ->first();

    if (!$employee) {
        return response()->json(['message' => 'Employee not found for this user/factory'], 404);
    }

    // ✅ Employee apni shift ke ellawa waqt me production submit nahi kar sakta
    // (Ye restriction sirf tab lagta hai jab employee khud (QR scan se) submit kar raha ho —
    //  agar owner/manager kisi employee ki taraf se enter kar raha hai to skip)
    $isSelfSubmission = $request->user() && $request->user()->id == $request->user_id;

    if ($isSelfSubmission && !$this->isWithinShift($employee->shift_starttime, $employee->shift_endtime)) {
        return response()->json([
            'message' => "You can only submit production during your shift ({$employee->shift_starttime} - {$employee->shift_endtime})",
        ], 403);
    }

    // ✅ Is employee ki is machine par apni khud ki latest row — yehi uska "current batch" batati hai
    //    (variety_type/total_length/batch_id client se nahi, isi row se aate hain — source of truth)
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
     


    // ✅ manager_id poori factory se dhoondo
    $managerId = Production::where('factory_id', $request->factory_id)
        ->whereNotNull('manager_id')
        ->latest()
        ->value('manager_id');

    // ✅ Remaining ab is BATCH ka hai — dono shift-employees mila kar (shared), sirf is employee ka nahi
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
            'employee_id' => $employee->id, // ✅ employees.id

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

        // ✅ Owner(s) ko notification bhejo — employee name + machine name ke sath
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
 
    // GET /api/payments/view-payments/{factoryId}
    public function viewPayments($factoryId)
    {
    $authUser = auth()->user();

    $factory = Factory::find($factoryId);
    if (!$factory) {
        return response()->json(['message' => 'Factory not found'], 404);
    }

$factoryName = $factory->name;

    $managerName = null;
    if ($factory->manager_id) {
        $manager = User::find($factory->manager_id);
        $managerName = $manager?->name;
    }

    // ---------- ROLE BASED ACCESS CONTROL (Spatie) ----------
    if ($authUser->hasRole('owner')) {
        // Admin: full access, no restriction

    } elseif ($authUser->hasRole('manager')) {
        // Manager: sirf apni assigned factory ka data dekh sakta hai
        if ((int) $factory->manager_id !== (int) $authUser->id) {
            return response()->json([
                'message' => 'Unauthorized: Aap sirf apni factory ke payments dekh sakte hain.'
            ], 403);
        }

    } elseif ($authUser->hasRole('employee')) {
        // Employee: neeche filter lagega, sirf apna data milega

    } else {
        return response()->json(['message' => 'Unauthorized role.'], 403);
    }
    // -----------------------------------------------------------

    $recordsQuery = Production::where('factory_id', $factoryId)
        ->whereNotNull('employee_id')
        ->orderBy('employee_id')
        ->orderByDesc('created_at');

    // Employee ko sirf apna data dikhayen
    if ($authUser->hasRole('employee')) {
        $employee = Employee::where('user_id', $authUser->id)->first();

        if (!$employee) {
            return response()->json(['message' => 'Employee profile not found.'], 404);
        }

        $recordsQuery->where('employee_id', $employee->id);
    }

    $records = $recordsQuery->get();

    // Fetch all machine names in a single query (avoids N+1 queries)
    $machineIds = $records->pluck('machine_id')->filter()->unique();
    $machines = Machine::whereIn('id', $machineIds)->pluck('machine_name', 'id');

    $grouped = $records->groupBy('employee_id')->map(function ($rows, $employeeId) use ($machines, $factoryName, $managerName) {
        $employee = Employee::find($employeeId);
        $employeeName = null;
        if ($employee) {
            $user = User::find($employee->user_id);
            $employeeName = $user?->name;
        }

        $productions = $rows->map(function ($row) use ($machines) {
            $totalLength = (float) $row->total_length;
            $rate = (float) ($row->amount_per_meter ?? 0);
            $ready = (float) $row->ready_production;
            $waste = (float) $row->waste_production;

            return [
                'production_id'        => $row->id,
                'batch_id'              => $row->batch_id,
                'variety_type'          => $row->variety_type,
                'total_length'          => $totalLength,
                'ready_production'      => (int) $row->ready_production,
                'waste_production'      => (float) $row->waste_production,
                    'remaining_production'  => $totalLength - $ready - $waste,
                'machine_name'          => $machines[$row->machine_id] ?? null,
                'amount_per_meter'      => $rate,
                'amount'                => $totalLength * $rate,
                'select_days'           => $row->select_days,
                'shift_start'           => $row->shift_start,
                'shift_end'             => $row->shift_end,
                'created_at'            => $row->created_at,
            ];
        })->values();

        return [
            'employee_id'    => (int) $employeeId,
            'employee_name'  => $employeeName,
            'factory_name'   => $factoryName,
            'manager_name'   => $managerName,
            'total_amount'   => $productions->sum('amount'),
            'total_length'   => $productions->sum('total_length'),
            'productions'    => $productions,
        ];
    })->values();

    return response()->json([
        'data' => $grouped,
    ]);
}
    // 9. Assign production (FIXED)
    public function assignProduction(Request $request)
    {
        $request->validate([
            'machine_id' => 'required|integer',
            'variety_type' => 'required|string',
            'total_length' => 'required|numeric',
            'amount_per_meter' => 'required|numeric',
            'select_days' => 'required|string',
        ]);

        // ✅ Is machine par ab tak jitne employees assign ho chuke hain (dono shifts),
        // un SABKO ye naya batch milta hai — batch machine ka hota hai, kisi ek employee ka nahi
        $employeeIds = Production::where('machine_id', $request->machine_id)
            ->whereNotNull('employee_id')
            ->distinct()
            ->pluck('employee_id');

        if ($employeeIds->isEmpty()) {
            return response()->json([
                'message' => 'Is machine par pehle koi employee assign karein (Assign Machine se), phir production batch assign karein.',
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
                'select_days' => $request->select_days,

                'ready_production' => 0,
                'waste_production' => 0,
                'remaining' => $request->total_length,

                'shift_start' => $employee->shift_starttime,
                'shift_end' => $employee->shift_endtime,

                'status' => 1,
            ]);

            $created[] = $production;   // 👈 append to the array (for count() later)

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

    // ✅ Helper: check karo current time employee ki shift window ke andar hai ya nahi
    // Overnight shifts (e.g. 20:00 - 08:00) ko bhi handle karta hai
    private function isWithinShift($start, $end)
    {
        if (!$start || !$end) {
            // Agar shift assign hi nahi hai to allow kar do (fail-open) taake purana data na tootay
            return true;
        }

        $now   = \Carbon\Carbon::now()->format('H:i:s');
        $start = \Carbon\Carbon::parse($start)->format('H:i:s');
        $end   = \Carbon\Carbon::parse($end)->format('H:i:s');

        if ($start <= $end) {
            // Normal shift (e.g. 08:00 - 20:00)
            return $now >= $start && $now <= $end;
        }

        // Overnight shift (e.g. 20:00 - 08:00 agle din)
        return $now >= $start || $now <= $end;
    }
}