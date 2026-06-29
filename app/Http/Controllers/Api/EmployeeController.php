<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Factory;
use App\Models\User;


class EmployeeController extends Controller
{
    /**
     * Get all employees (optional admin use)
     */
    public function index()
    {
        return Employee::with('user')->get();
    }

    public function factories()
    {
        return Factory::select('id','name')->get();
    }


    public function users()
    {
        return User::role('employee')
            ->select('id','name','email')
            ->orderBy('name')
            ->get();
    }


   public function store(Request $request)
    {
        $request->validate([
            'factory_id' => 'required|exists:factories,id',
            'user_id' => 'required|exists:users,id',
            'shift_starttime' => 'required',
            'shift_endtime' => 'required',
        ]);

        // ✅ pehle purana record delete karo (same user ka)
        Employee::where('user_id', $request->user_id)->delete();

        // ✅ phir naya record insert karo
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
            'factory_id' => $request->factory_id,
            'user_id' => $request->user_id,
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