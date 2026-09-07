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
use Carbon\Carbon;

class ApproveProductionController extends Controller
{
    // STATUS REFERENCE:
    //   1 = employee submitted (pending)
    //   2 = manager approved
    //   3 = manager rejected
    //   4 = owner approved
    //   5 = owner rejected


    // MANAGER get all productions for factory (grouped by employee -> machine, period filter ke saath)
    public function managerProductions(Request $request, $factoryId)
    {
        $factory = Factory::find($factoryId);
        if (!$factory) {
            return response()->json(['message' => 'Factory not found'], 404);
        }

        $validEmployeeIds = Employee::where('factory_id', $factoryId)->pluck('id');
        $validMachineIds  = Machine::where('factory_id', $factoryId)->pluck('id');

        $query = Production::where('factory_id', $factoryId)
            ->whereIn('status', [1, 2, 4]) // manager view: pending, mgr-approved, owner-approved
            ->whereIn('employee_id', $validEmployeeIds)
            ->whereIn('machine_id', $validMachineIds)
            ->with(['employeedetails.user', 'machineemploye']);

        // ---- Period filter (?period=this_week etc.) ----
        $period = $request->query('period');
        if ($period) {
            [$start, $end] = $this->periodRange($period);
            if ($start && $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }
        }

        $productions = $query->latest()->get();

        // Employee-wise group
        $employees = $productions->groupBy('employee_id')->map(function ($rows, $employeeId) {
            $first        = $rows->first();
            $employeeName = optional(optional($first->employeedetails)->user)->name
                ?? "Emp #$employeeId";

            // Machine-wise group
            $machineGroups = $rows->groupBy('machine_id')->map(function ($machineRows, $machineId) {
                $machineName = optional($machineRows->first()->machineemploye)->machine_name
                    ?? "Machine #$machineId";

                // Manager ke liye: pending = status 1, approved = status 2 ya 4 (mgr or owner approved)
                $pendingRows  = $machineRows->where('status', 1);
                $approvedRows = $machineRows->whereIn('status', [2, 4]);

                $mapProd = function ($p) {
                    $total = (float) ($p->total_length ?? 0);
                    $ready = (float) ($p->ready_production ?? 0);
                    $waste = (float) ($p->waste_production ?? 0);

                    return [
                        'id'                => $p->id,
                        'batch_id'          => $p->batch_id,
                        'variety_type'      => $p->variety_type,
                        'status'            => (int) $p->status,
                        'total_length'      => $total,
                        'ready_production'  => $ready,
                        'waste_production'  => $waste,
                        'remaining'         => max(0, $total - $ready - $waste),
                        'created_at'        => $p->created_at,
                        'updated_at'        => $p->updated_at,
                    ];
                };

                return [
                    'machine_id'       => $machineId ? (int) $machineId : null,
                    'machine_name'     => $machineName,
                    'pending_count'    => $pendingRows->count(),
                    'approved_count'   => $approvedRows->count(),
                    'production_count' => $machineRows->count(),
                    'pending'          => $pendingRows->map($mapProd)->values(),
                    'approved'         => $approvedRows->map($mapProd)->values(),
                ];
            })->values();

            return [
                'employee_id'    => (int) $employeeId,
                'employee_name'  => $employeeName,
                'machine_count'  => $machineGroups->count(),
                'pending_count'  => $machineGroups->sum('pending_count'),
                'approved_count' => $machineGroups->sum('approved_count'),
                'machines'       => $machineGroups,
            ];
        })->values();

        return response()->json([
            'status'    => true,
            'employees' => $employees,
        ]);
    }

    // MANAGER: approve or reject
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

    $managerName = $request->user()->name ?? 'Manager';

    // notification employee ko bhejo
    $employeeUserId = $prod->employeedetails->user->id ?? null;

    if ($employeeUserId) {
        Notification::create([
            'user_id'        => $employeeUserId,
            'production_id'  => $prod->id,
            'sender_id'      => $request->user()->id,
            'title'          => $request->action === 'approve' ? 'Production Approved' : 'Production Rejected',
            'message'        => $request->action === 'approve'
                ? "Your production has been approved by $managerName"
                : "Your production has been rejected by $managerName",
            'type'           => $request->action === 'approve' ? 'approved' : 'rejected',
        ]);
    }

    // owner(s) ko bhi batao
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
                    ? "$managerName approved $employeeName's production on \"$machineName\""
                    : "$managerName rejected $employeeName's production on \"$machineName\"",
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
    // Owner get all productions for factory (period filter ke saath)
    public function ownerProductions(Request $request, $factoryId)
    {
        $factory = Factory::find($factoryId);
        if (!$factory) {
            return response()->json(['message' => 'Factory not found'], 404);
        }

        $validEmployeeIds = Employee::where('factory_id', $factoryId)->pluck('id');
        $validMachineIds  = Machine::where('factory_id', $factoryId)->pluck('id');

        $query = Production::where('factory_id', $factoryId)
            ->whereIn('status', [1, 2, 3, 4, 5])
            ->whereIn('employee_id', $validEmployeeIds)
            ->whereIn('machine_id', $validMachineIds)
            ->with(['employeedetails.user', 'machineemploye']);

        $period = $request->query('period');
        if ($period) {
            [$start, $end] = $this->periodRange($period);
            if ($start && $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }
        }

        $productions = $query->latest()->get();

        $employees = $productions->groupBy('employee_id')->map(function ($rows, $employeeId) {
            $first        = $rows->first();
            $employeeName = optional(optional($first->employeedetails)->user)->name
                ?? "Emp #$employeeId";

            $machineGroups = $rows->groupBy('machine_id')->map(function ($machineRows, $machineId) {
                $machineName = optional($machineRows->first()->machineemploye)->machine_name
                    ?? "Machine #$machineId";

                $pendingRows  = $machineRows->whereNotIn('status', [4, 5]);
                $approvedRows = $machineRows->where('status', 4);

                $mapProd = function ($p) {
                    $total = (float) ($p->total_length ?? 0);
                    $ready = (float) ($p->ready_production ?? 0);
                    $waste = (float) ($p->waste_production ?? 0);

                    return [
                        'id'                => $p->id,
                        'batch_id'          => $p->batch_id,
                        'variety_type'      => $p->variety_type,
                        'status'            => (int) $p->status,
                        'total_length'      => $total,
                        'ready_production'  => $ready,
                        'waste_production'  => $waste,
                        'remaining'         => max(0, $total - $ready - $waste),
                        'created_at'        => $p->created_at,
                        'updated_at'        => $p->updated_at,
                    ];
                };

                return [
                    'machine_id'       => $machineId ? (int) $machineId : null,
                    'machine_name'     => $machineName,
                    'pending_count'    => $pendingRows->count(),
                    'approved_count'   => $approvedRows->count(),
                    'production_count' => $machineRows->count(),
                    'pending'          => $pendingRows->map($mapProd)->values(),
                    'approved'         => $approvedRows->map($mapProd)->values(),
                ];
            })->values();

            return [
                'employee_id'    => (int) $employeeId,
                'employee_name'  => $employeeName,
                'machine_count'  => $machineGroups->count(),
                'pending_count'  => $machineGroups->sum('pending_count'),
                'approved_count' => $machineGroups->sum('approved_count'),
                'machines'       => $machineGroups,
            ];
        })->values();

        return response()->json([
            'status'    => true,
            'employees' => $employees,
        ]);
    }

    /**
     * Period key -> [start, end] Carbon range. Shared by owner + manager.
     */
    private function periodRange(string $period): array
    {
        $now = Carbon::now();

        return match ($period) {
            'this_week'      => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'previous_week'  => [$now->copy()->subWeek()->startOfWeek(), $now->copy()->subWeek()->endOfWeek()],
            'this_month'     => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'previous_month' => [$now->copy()->subMonth()->startOfMonth(), $now->copy()->subMonth()->endOfMonth()],
            'this_year'      => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            'previous_year'  => [$now->copy()->subYear()->startOfYear(), $now->copy()->subYear()->endOfYear()],
            default          => [null, null],
        };
    }

    //Owner approve or reject 
  public function ownerAction(Request $request, $id)
{
    $request->validate(['action' => 'required|in:approve,reject']);

    $prod = Production::with('employeedetails.user')->findOrFail($id);

    if ($request->action === 'approve') {
        $prod->status = 4;
        $prod->earned_amount = $prod->ready_production * $prod->amount_per_meter;
    } else {
        $prod->status = 5;
        $prod->earned_amount = 0;
    }
    $prod->save();

    $ownerName = $request->user()->name ?? 'Owner';

    try {
        $employeeUserId = $prod->employeedetails->user->id ?? null;

        if ($employeeUserId) {
            Notification::create([
                'user_id'        => $employeeUserId,
                'production_id'  => $prod->id,
                'sender_id'      => $request->user()->id,
                'title'          => $request->action === 'approve' ? 'Production Approved' : 'Production Rejected',
                'message'        => $request->action === 'approve'
                    ? "Your production has been approved by $ownerName"
                    : "Your production has been rejected by $ownerName",
                'type'           => $request->action === 'approve' ? 'approved' : 'rejected',
            ]);
        }
    } catch (\Exception $e) {
        \Log::error('Notification create failed: ' . $e->getMessage());
    }

    try {
        if ($prod->manager_id) {
            $machineName  = optional($prod->machineemploye)->machine_name ?? 'Machine';
            $employeeName = optional($prod->employeedetails)->user->name ?? 'Employee';

            Notification::create([
                'user_id'       => $prod->manager_id,
                'production_id' => $prod->id,
                'sender_id'     => $request->user()->id,
                'title'         => $request->action === 'approve' ? 'Owner Approved Production' : 'Owner Rejected Production',
                'message'       => $request->action === 'approve'
                    ? "$ownerName approved $employeeName's production on \"$machineName\""
                    : "$ownerName rejected $employeeName's production on \"$machineName\"",
                'type'          => $request->action === 'approve' ? 'approved' : 'rejected',
            ]);
        }
    } catch (\Exception $e) {
        \Log::error('Manager notification create failed: ' . $e->getMessage());
    }

    return response()->json([
        'message' => $request->action === 'approve' ? 'Owner Approved' : 'Owner Rejected',
        'production' => $prod,
    ]);
}
}