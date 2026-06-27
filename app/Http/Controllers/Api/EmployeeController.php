<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Get all employees (optional admin use)
     */
    public function index()
    {
        return Employee::with('user')->get();
    }

    /**
     * Store employee shift assignment
     */
   public function store(Request $request)
{
    $request->validate([
        'factory_id' => 'required|exists:factories,id',
        'user_id' => 'required|exists:users,id',
        'shift_starttime' => 'required',
        'shift_endtime' => 'required',
    ]);

    $employee = Employee::create([
        'factory_id' => $request->factory_id,
        'user_id' => $request->user_id,
        'shift_starttime' => $request->shift_starttime,
        'shift_endtime' => $request->shift_endtime,
        'timestamp' => now(),
    ]);

    return response()->json([
        'message' => 'Employee shift assigned successfully',
        'data' => $employee
    ], 201); 
}

    /**
     * Get employees by factory (🔥 MAIN API YOU NEED)
     */
    public function byFactory($factoryId)
    {
        return Employee::with('user')
            ->where('factory_id', $factoryId)
            ->get();
    }

    /**
     * Update employee shift
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $employee->update([
            'shift_starttime' => $request->shift_starttime,
            'shift_endtime' => $request->shift_endtime,
        ]);

        return response()->json([
            'message' => 'Updated successfully',
            'data' => $employee
        ]);
    }

    /**
     * Delete employee assignment
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}