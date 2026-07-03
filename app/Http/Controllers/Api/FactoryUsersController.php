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
        $managerId = Production::where('factory_id', $factoryId)
            ->whereNotNull('manager_id')
            ->orderByDesc('id')
            ->value('manager_id');

        $manager = $managerId
            ? User::with('roles')->find($managerId)
            : null;

        $employees = \App\Models\Employee::where('factory_id', $factoryId)->get();
        $employeeUserIds = $employees->pluck('user_id');

        // ✅ "Active" = jis employee ne aaj (last 24 ghantay) machine scan kar ke
        // production submit ki ho
        $activeEmployeeIds = Production::where('factory_id', $factoryId)
            ->where('created_at', '>=', now()->subHours(24))
            ->pluck('employee_id')
            ->unique();

        $activeUserIds = $employees
            ->whereIn('id', $activeEmployeeIds)
            ->pluck('user_id');

        $users = User::with('roles')
            ->whereIn('id', $employeeUserIds)
            ->get()
            ->map(function ($user) use ($employees, $activeUserIds) {
                $emp = $employees->firstWhere('user_id', $user->id);
                $arr = $user->toArray();
                $arr['employee_id'] = $emp?->id;
                $arr['is_active']   = $activeUserIds->contains($user->id);
                return $arr;
            });

        return response()->json([
            'manager'      => $manager,
            'data'         => $users,
            'total_users'  => $users->count(),
            'active_users' => $activeUserIds->count(),
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