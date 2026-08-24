<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Factory;
use App\Models\Machine;
use App\Models\Employee;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    // get owner profile
    public function profile($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'Owner not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id'               => $user->id,
                'name'             => $user->name,
                'email'            => $user->email,
                'phone_no'         => $user->phone_no,
                'address'          => $user->address,
                'pic'              => $user->pic,

                'total_factories'  => Factory::count(),
                'total_machines'   => Machine::count(),
                'total_employees'  => Employee::whereHas('user', function ($q) {
                    $q->role('employee');
                })->count(),
                'total_managers'   => User::role('manager')->count(),
            ],
        ]);
    }
}