<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class PermissionController extends Controller
{
    // 1. Saari Permissions fetch karein
    public function getAllPermissions() {
        return response()->json([
            'status' => true,
            'data' => Permission::all()
        ]);
    }

    // 2. Kisi khas Role ki permissions fetch karein
    public function getRolePermissions($roleId) {
        // findById ki jagah find use karein jo zyada reliable hai
        $role = Role::find($roleId); 
        
        if (!$role) {
            return response()->json(['status' => false, 'message' => 'Role not found'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $role->permissions->pluck('id') 
        ]);
    }

    // 3. Role ko Permissions assign/sync karein
    public function syncPermissions(Request $request) {
        // Validation lazmi karein taake error na aaye
        $request->validate([
            'role_id' => 'required',
            'permissions' => 'required|array'
        ]);

        $role = Role::find($request->role_id);

        if ($role) {
            $role->syncPermissions($request->permissions); 
            return response()->json(['status' => true, 'message' => 'Permissions Updated!']);
        }

        return response()->json(['status' => false, 'message' => 'Role not found'], 404);
    }
}