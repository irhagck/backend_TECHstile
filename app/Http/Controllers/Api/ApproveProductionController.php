<?php
// app/Http/Controllers/ProductionController.php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Production;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Machine;
use App\Models\Factory;
use App\Models\Notification;
class ApproveProductionController extends Controller
{
    // =========================================================
    // STATUS REFERENCE:
    //   1 = employee submitted (pending)
    //   2 = manager approved
    //   3 = manager rejected
    //   4 = owner approved
    //   5 = owner rejected
    // =========================================================

    // ── MANAGER: get all productions for factory ─────────────
    // GET /api/manager/productions/{factoryId}
public function managerProductions($factoryId)
{
    // Factory exist karti hai ya nahi
    $factory = Factory::find($factoryId);

    if (!$factory) {
        return response()->json([
            'message' => 'Factory not found'
        ], 404);
    }

    // Isi factory ke employees
    $validEmployeeIds = Employee::where(
        'factory_id',
        $factoryId
    )->pluck('id');

    // Isi factory ki machines
    $validMachineIds = Machine::where(
        'factory_id',
        $factoryId
    )->pluck('id');

    // Productions
    $productions = Production::where(
            'factory_id',
            $factoryId
        )
        ->whereIn('status', [1, 2, 4])
        ->whereIn('employee_id', $validEmployeeIds)
        ->whereIn('machine_id', $validMachineIds)
        ->with([
            'employeedetails.user',
            'machineemploye'
        ])
        ->latest()
        ->get();

    return response()->json([
        'status' => true,
        'productions' => $productions
    ]);
}
    // ── MANAGER: approve or reject ────────────────────────────
    // POST /api/manager/productions/{id}/action
    // body: { "action": "approve" | "reject" }
    public function managerAction(Request $request, $id)
{
    $request->validate(['action' => 'required|in:approve,reject']);

    $prod = Production::with('employeedetails.user')->findOrFail($id);

    if ($prod->status == 4) {
        return response()->json([
            'message' => 'Owner has already approved this production',
            'production' => $prod,
        ]);
    }

    $prod->status = $request->action === 'approve' ? 2 : 3;
    $prod->save();

    // ── notification employee ko bhejo ──
    $employeeUserId = $prod->employeedetails->user->id ?? null;

    if ($employeeUserId) {
        Notification::create([
            'user_id'        => $employeeUserId,
            'production_id'  => $prod->id,
            'sender_id'      => $request->user()->id, // manager ka id (auth se)
            'title'          => $request->action === 'approve' ? 'Production Approved' : 'Production Rejected',
            'message'        => $request->action === 'approve'
                ? 'Your production has been approved by manager'
                : 'Your production has been rejected by manager',
            'type'           => $request->action === 'approve' ? 'approved' : 'rejected',
        ]);
    }

    // ── ✅ owner(s) ko bhi batao k manager ne approve/reject kiya ──
    try {
        $machineName  = optional($prod->machineemploye)->machine_name ?? 'Machine';
        $employeeName = optional($prod->employeedetails)->user->name ?? 'Employee';

        $owners = \App\Models\User::role('owner')->get();
        foreach ($owners as $owner) {
            Notification::create([
                'user_id'       => $owner->id,
                'production_id' => $prod->id,
                'sender_id'     => $request->user()->id,
                'title'         => $request->action === 'approve' ? 'Manager Approved Production' : 'Manager Rejected Production',
                'message'       => $request->action === 'approve'
                    ? "Manager approved $employeeName's production on machine \"$machineName\""
                    : "Manager rejected $employeeName's production on machine \"$machineName\"",
                'type'          => $request->action === 'approve' ? 'approved' : 'rejected',
            ]);
        }
    } catch (\Exception $e) {
        \Log::error('Owner notification create failed: ' . $e->getMessage());
    }

    return response()->json([
        'message' => $request->action === 'approve' ? 'Approved' : 'Rejected',
        'production' => $prod,
    ]);
}
    // ── OWNER: get all productions for factory ────────────────
    // GET /api/owner/productions/{factoryId}
  public function ownerProductions($factoryId)
{
    // ✅ Sirf isi factory ke valid employee IDs
    $validEmployeeIds = Employee::where('factory_id', $factoryId)
        ->pluck('id');

    // ✅ Sirf isi factory ki valid machine IDs
    $validMachineIds = Machine::where('factory_id', $factoryId)
        ->pluck('id');

    $productions = Production::where('factory_id', $factoryId)
        ->whereIn('status', [1, 2, 3, 4]) // owner sab dekh sakta hai — pending/approved/rejected sab
        ->whereIn('employee_id', $validEmployeeIds)
        ->whereIn('machine_id', $validMachineIds)
        ->with([
            'employeedetails.user', // ✅ sahi relation names
            'machineemploye'
        ])
        ->latest()
        ->get();

    return response()->json([
        'status'      => true,
        'productions' => $productions
    ]);
}
    // ── OWNER: approve or reject ──────────────────────────────
    // POST /api/owner/productions/{id}/action
    // body: { "action": "approve" | "reject" }
   public function ownerAction(Request $request, $id)
{
    $request->validate(['action' => 'required|in:approve,reject']);

    $prod = Production::with('employeedetails.user')->findOrFail($id);

    $prod->status = $request->action === 'approve' ? 4 : 5;
    $prod->save();

    // ── notification (wrapped, production approval isse affect nahi hoga) ──
    try {
        $employeeUserId = $prod->employeedetails->user->id ?? null;

        if ($employeeUserId) {
            Notification::create([
                'user_id'        => $employeeUserId,
                'production_id'  => $prod->id,
                'sender_id'      => $request->user()->id,
                'title'          => $request->action === 'approve' ? 'Production Approved' : 'Production Rejected',
                'message'        => $request->action === 'approve'
                    ? 'Your production has been approved by owner'
                    : 'Your production has been rejected by owner',
                'type'           => $request->action === 'approve' ? 'approved' : 'rejected',
            ]);
        }
    } catch (\Exception $e) {
        \Log::error('Notification create failed: ' . $e->getMessage());
    }

    return response()->json([
        'message' => $request->action === 'approve' ? 'Owner Approved' : 'Owner Rejected',
        'production' => $prod,
    ]);
}
    
}