<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Production;
use App\Models\User;
use App\Models\Employee;       // ← ye add karo upar

class MachineAssignmentController extends Controller
{
    // ✅ Naya function - employees jo table mein hain
    public function getAssignableEmployees()
    {
        $employeeUserIds = \App\Models\Employee::pluck('user_id')->unique();

        $users = User::role('employee')
                     ->whereIn('id', $employeeUserIds)
                     ->select('id', 'name', 'phone_no', 'email')
                     ->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    // ✅ Purana function same rahega - kuch mat chhedo
    public function assignMachines(Request $request)
    {
        $request->validate([
            'manager_id'   => 'required|integer',
            'employee_id' => 'required|integer',
            'factory_id'   => 'required|integer',
            'machine_ids'  => 'required|array',
        ]);

        $employee = Employee::find($request->employee_id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        foreach ($request->machine_ids as $machineId) {

            $exists = Production::where('employee_id', $employee->id)
                ->where('machine_id', $machineId)
                ->exists();

            if ($exists) continue;

            // ✅ Sirf assignment banti hai — total_length/variety_type abhi NULL rahenge,
            // "Assign Production Batch" se baad me set honge (machine ke liye shared)
            Production::create([
                'manager_id' => $request->manager_id,
                'employee_id' => $employee->id,   // ✅ employee table ki id
                'factory_id' => $request->factory_id,
                'machine_id' => $machineId,

                'variety_type' => null,
                'total_length' => null,
                'batch_id' => null,

                'ready_production' => 0,
                'waste_production' => 0,
                'remaining' => 0,

                'shift_start' => $employee->shift_starttime,
                'shift_end'   => $employee->shift_endtime,

                'status' => 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Machines assigned via production pivot table'
        ]);
    }
}