<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Factory;
use App\Models\Payment;
use App\Models\User;
use App\Models\Production;


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


    public function earnedAmount($id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        // Sirf owner approved productions (status 4) ka earned amount calculate karo
        $approvedProductions = Production::where('employee_id', $id)
            ->where('status', 4)
            ->get();

        $totalEarned = 0;
        foreach ($approvedProductions as $prod) {
            if ($prod->earned_amount !== null && $prod->earned_amount > 0) {
                $totalEarned += (float) $prod->earned_amount;
            } else {
                $totalEarned += (float) ($prod->ready_production * $prod->amount_per_meter);
            }
        }

        $totalPaid = (float) Payment::where('employee_id', $id)->sum('amount_paid');
        $remaining = max(0, $totalEarned - $totalPaid);

        return response()->json([
            'employee_id'  => (int) $id,
            'total_earned' => (float) $totalEarned,
            'total_paid'   => (float) $totalPaid,
            'remaining'    => (float) $remaining,
        ]);
    }


    public function users()
    {
        return User::role('employee')
            ->select('id','name','email')
            ->orderBy('name')
            ->get();
    }

    public function employeesWithShiftByFactory($factoryId)
    {
        $employees = \App\Models\Employee::with('user')
            ->where('factory_id', $factoryId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $employees->map(function ($e) {
                return [
                    'id' => $e->id,               // ✅ Employee table ki ID
                    'user_id' => $e->user_id,     // Optional, agar kabhi zarurat ho
                    'name' => $e->user->name ?? 'Unknown',
                    'shift_starttime' => $e->shift_starttime,
                    'shift_endtime' => $e->shift_endtime,
                ];
            })
        ]);
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
        $employees = Employee::with('user')
            ->where('factory_id', $factoryId)
            ->get();

        $activeEmployeeIds = \App\Models\Production::where('factory_id', $factoryId)
            ->where('created_at', '>=', now()->subHours(24))
            ->pluck('employee_id')
            ->unique();

        $mapped = $employees->map(function ($e) use ($activeEmployeeIds) {
            $arr = $e->toArray();
            $arr['is_active'] = $activeEmployeeIds->contains($e->id);
            return $arr;
        });

        return response()->json([
            'data'              => $mapped,
            'total_employees'   => $mapped->count(),
            'active_employees'  => $activeEmployeeIds->count(),
        ]);
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