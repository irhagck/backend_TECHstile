<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Production;
use Illuminate\Http\Request;

class FactoryUsersController extends Controller
{
    // get factory users (manager + employees) with active status
  public function getUsersByFactory($factoryId)
{
    // Manager
    $managerId = Production::where('factory_id', $factoryId)
        ->whereNotNull('manager_id')
        ->orderByDesc('id')
        ->value('manager_id');

    $manager = $managerId
        ? User::with('roles')->find($managerId)
        : null;


    // Factory ke employees
    $employees = \App\Models\Employee::where(
        'factory_id',
        $factoryId
    )->get();

    $employeeUserIds = $employees->pluck('user_id');

    /*
    |--------------------------------------------------------------------------
    | LAST 12 HOURS SHIFT CHECK
    |--------------------------------------------------------------------------
    | Pichle 12 ghante me jis employee ki 'IN' attendance lagi hai,
    | sirf wahi active hoga (current shift employee).
    */
    $activeEmployeeIds = \App\Models\Attendence::whereIn(
        'employee_id',
        $employees->pluck('id')
    )
        ->where('type', 'IN')
        ->where('created_at', '>=', now()->subHours(12)) 
        ->pluck('employee_id')
        ->unique();


    // Employee IDs ko User IDs mein convert karna
    $activeUserIds = $employees
        ->whereIn('id', $activeEmployeeIds)
        ->pluck('user_id');


    /*
    |--------------------------------------------------------------------------
    | ALL FACTORY EMPLOYEES
    |--------------------------------------------------------------------------
    */

    $users = User::with('roles')
        ->whereIn('id', $employeeUserIds)
        ->get()
        ->map(function ($user) use (
            $employees,
            $activeUserIds
        ) {

            $emp = $employees->firstWhere(
                'user_id',
                $user->id
            );

            $arr = $user->toArray();

            $arr['employee_id'] = $emp?->id;

            $arr['is_active'] = $activeUserIds->contains($user->id);

            return $arr;
        });


    return response()->json([
        'manager' => $manager,

        'data' => $users,

        'total_users' => $users->count(),

        'active_users' => $activeUserIds->count(),
    ]);
}

    // Only this factory employees, with their shift timings
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