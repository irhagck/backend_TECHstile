<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    // show all roles list
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json([
            'status' => true,
            'data' => $roles
        ], 200);
    }

    // create a new role
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permissions' => 'array' // Permissions IDs bheji ja sakti hain
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json(['status' => true, 'message' => 'Role Created Successfully', 'data' => $role], 201);
    }

    // 3. Single Role detail(edit delete)
    public function show($id)
    {
        $role = Role::with('permissions')->find($id);
        if (!$role) {
            return response()->json(['status' => false, 'message' => 'Role not found'], 404);
        }
        return response()->json(['status' => true, 'data' => $role], 200);
    }

    // 4. Role Update 
    public function update(Request $request, $id)
    {
        $role = Role::findById($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'array'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $role->update(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json(['status' => true, 'message' => 'Role Updated Successfully'], 200);
    }

    // 5. Role Delete 
    public function destroy($id)
    {
        $role = Role::findById($id);
        $role->delete();
        return response()->json(['status' => true, 'message' => 'Role Deleted Successfully'], 200);
    }
}