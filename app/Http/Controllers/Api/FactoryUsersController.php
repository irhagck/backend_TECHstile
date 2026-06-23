<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Production;
use App\Models\User;

class FactoryUsersController extends Controller
{
    public function usersByFactory($factoryId)
    {
        //  Get Manager (latest assigned)
        $managerId = Production::where('factory_id', $factoryId)
            ->whereNotNull('manager_id')
            ->latest()
            ->value('manager_id');

        $manager = $managerId
            ? User::with('roles')->find($managerId)
            : null;

        //  Get Employees
        $employeeIds = Production::where('factory_id', $factoryId)
            ->pluck('employee_id')
            ->unique();

        $users = User::with('roles')
            ->whereIn('id', $employeeIds)
            ->get();

        return response()->json([
            'success' => true,

            // stats
            'total_users' => $users->count(),
            'active_users' => $users->count(),
            'manager' => $manager,
            'data' => $users,
        ]);
    }
}