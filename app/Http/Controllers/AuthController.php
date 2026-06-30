<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Production;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = User::where('email', $request->email)->with('roles')->first();

        // User exist nahi karta ya password galat hai
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $role  = $user->roles->first()?->name;
        $factoryId = Production::where(
    'manager_id',
    $user->id
)->value('factory_id');

\Log::info([
    'manager_id' => $user->id,
    'factory_id' => $factoryId
]);

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'user'  => [
                    'id'       => $user->id,
                    'name'     => $user->name,
                    'email'    => $user->email,
                    'phone_no' => $user->phone_no,
                    'cnic'     => $user->cnic,
                    'address'  => $user->address,
                    'pic'      => $user->pic,
                    'roles'    => $user->roles, // Flutter side roles[0]['name'] ke liye
                    'role'     => $role, 
                   'factory_id' => $factoryId,      // Direct role name bhi
                ]
            ]
        ], 200);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'user'    => $request->user()->load('roles')
        ], 200);
    }
}