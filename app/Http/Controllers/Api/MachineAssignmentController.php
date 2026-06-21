<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Production;

class MachineAssignmentController extends Controller
{
    public function assignMachines(Request $request)
    {
        $request->validate([
            'manager_id'  => 'required|integer',
            'user_id'     => 'required|integer',
            'factory_id'  => 'required|integer',
            'machine_ids' => 'required|array',
        ]);

        foreach ($request->machine_ids as $machineId) {

            // ❗ avoid duplicate assignment (important)
            $exists = Production::where('employee_id', $request->user_id)
                ->where('machine_id', $machineId)
                ->whereNull('shift_end')
                ->exists();

            if ($exists) continue;

            Production::create([
             'manager_id'  => $request->manager_id,
             'employee_id' => $request->user_id,
             'factory_id'  => $request->factory_id,
             'machine_id'  => $machineId,

             'variety_type'     => 'assigned',
             'total_length'     => 0,
             'ready_production' => 0,

             'shift_start' => now(),
             'shift_end'   => null,
           ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Machines assigned via production pivot table'
        ]);
    }
}