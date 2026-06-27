<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Production;
use Illuminate\Http\Request;

class FactoryUsersController extends Controller
{
    // ✅ GET /api/factory-users/{factoryId}
    // Manager + saare users jo isi factory se related hain
    public function getUsersByFactory($factoryId)
    {
        // Manager dhoondo — production table ke manager_id se
        $managerId = Production::where('factory_id', $factoryId)
            ->value('manager_id');

        $manager = $managerId
            ? User::with('roles')->find($managerId)
            : null;

        // Saare users jo employees table se isi factory se link hain
        $employeeUserIds = \App\Models\Employee::where('factory_id', $factoryId)
            ->pluck('user_id');

        $users = User::with('roles')
            ->whereIn('id', $employeeUserIds)
            ->get();

        return response()->json([
            'manager'      => $manager,
            'data'         => $users,
            'total_users'  => $users->count(),
            'active_users' => $users->count(), // abhi sab active treat karo
        ]);
    }

    // ✅ GET /api/employees-by-factory/{factoryId}
    // Sirf isi factory ke employees — dropdown ke liye (name + id)
    public function getEmployeesByFactory($factoryId)
    {
        $employees = \App\Models\Employee::with('user')
            ->where('factory_id', $factoryId)
            ->get()
           ->map(function ($emp) {
    return [
        'id'              => $emp->id,
        'user_id'         => $emp->user_id,  // ← ye line hai ya nahi?
        'name'            => $emp->user?->name ?? 'Employee #' . $emp->id,
        'shift_starttime' => $emp->shift_starttime,
        'shift_endtime'   => $emp->shift_endtime,
    ];
});

        return response()->json([
            'status' => true,
            'data'   => $employees,
        ]);
    }
}