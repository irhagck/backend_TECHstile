<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    //  All Roles
    public function index()
    {
        $roles = Role::where('guard_name', 'web')->with('permissions')->get();
        return response()->json(['status' => true, 'data' => $roles], 200);
    }

    // Add Role
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
        }

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json(['status' => true, 'message' => 'Role created', 'data' => $role], 200);
    }

    // Show Role
    public function show($id)
    {
        $role = Role::where('id', $id)->where('guard_name', 'web')->with('permissions')->first();

        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Role not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $role], 200);
    }

    // Update Role
    public function update(Request $request, $id)
    {
        // specify web guard
        $role = Role::where('id', $id)->where('guard_name', 'web')->first();

        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Role not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'        => 'required|unique:roles,name,' . $id,
            'permissions' => 'array'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
        }

        $role->name = $request->name;
        $role->save();

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json(['status' => true, 'message' => 'Role updated', 'data' => $role], 200);
    }

    //Delete Role
    public function destroy($id)
    {
        
        $role = Role::where('id', $id)->where('guard_name', 'web')->first();

        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Role not found'], 404);
        }

        $role->delete();

        return response()->json(['status' => true, 'message' => 'Role deleted'], 200);
    }
}