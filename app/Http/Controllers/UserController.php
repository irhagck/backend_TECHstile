<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
        public function managers()
{
    $users = User::role('manager')->get();

    return response()->json([
        'success' => true,
        'data' => $users
    ]);
}

public function employees()
{
    // only that users which is in employees table
    $employeeUserIds = \App\Models\Employee::pluck('user_id')->unique();

    $users = User::role('employee')
                 ->whereIn('id', $employeeUserIds)
                 ->get();

    return response()->json([
        'success' => true,
        'data' => $users
    ]);
}
    // Show All Users
   public function index()
{
    $users = User::with('roles')->get();

    return response()->json([
        'success' => true,
        'data' => $users
    ], 200);
}

    // Add User
    public function store(Request $request)
    {
        $request->validate([
         'name'              => 'required|string|max:255',
         'email'             => 'required|email|unique:users,email',
         'password'          => 'required|min:6',
         'phone_no'          => 'required|string|max:20',
         'cnic'              => 'required|string|max:20|unique:users,cnic',
         'address'           => 'nullable|string',
        //  'pic'               => 'nullable|string',
         'role'              => 'required|string',
         'employee_details'  => 'nullable|string',
        ]);

        $user = User::create([
         'name'              => $request->name,
         'email'             => $request->email,
         'password'          => Hash::make($request->password),
         'phone_no'          => $request->phone_no,
         'cnic'              => $request->cnic,
         'address'           => $request->address,
        //  'pic'               => $request->pic,
         'role'              => $request->role,
         'employee_details'  => $request->employee_details,
        ]);
        if ($request->filled('role')) {
    $user->syncRoles([$request->role]);
}

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
           'data'    => $user->load('roles')
        ], 201);
    }

    //Get Single User (Edit)
    public function edit($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $user
        ], 200);
    }
public function update(Request $request, $id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404);
    }

    $request->validate([
        'name'             => 'sometimes|string|max:255',
        'email'            => 'sometimes|email|unique:users,email,' . $id,
        'password'         => 'sometimes|min:6',
        'phone_no'         => 'sometimes|string|max:20',
        'cnic'             => 'sometimes|string|max:20|unique:users,cnic,' . $id,
        'address'          => 'nullable|string',
    
        'role'             => 'sometimes|string|exists:roles,name', 
        'employee_details' => 'nullable|string',
    ]);

    $user->name             = $request->name             ?? $user->name;
    $user->email            = $request->email            ?? $user->email;
    $user->phone_no         = $request->phone_no         ?? $user->phone_no;
    $user->cnic             = $request->cnic             ?? $user->cnic;
    $user->address          = $request->address          ?? $user->address;
  
    $user->employee_details = $request->employee_details ?? $user->employee_details;
    //  Assign role from Spatie 
    if ($request->filled('role')) {
        $user->syncRoles([$request->role]);
    }

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'User updated successfully',
        'data'    => $user->load('roles') // ✅ roles bhi return karo
    ], 200);
}

    // Delete User
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ], 200);
    }
    public function employeesInTable()
{
    // only that users that is occure in employees table
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
}
