<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // LOGIN WITH ROLES
      public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
        ], 400);
    }
 $user = User::where('email', $request->email)->with('roles')->first();


return response()->json([
    'success' => true,
    'data' => [
        'token' => $user->createToken('auth_token')->plainTextToken,
        'user' => $user // Access complete 'roles' record with user record
    ]
]);
 /// TOKEN
    $token = $user->createToken('auth_token')->plainTextToken;

    /// ROLE NAME
    $role = $user->roles->first()?->name;

    return response()->json([

        'success' => true,

        'token' => $token,

        'user' => [

            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone_no' => $user->phone_no,
            'cnic' => $user->cnic,
            'address' => $user->address,
            'pic' => $user->pic,

            /// SIMPLE ROLE
            'role' => $role,
        ]

    ], 200);
}

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ], 200);
    }
}
  