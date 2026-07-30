<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Production;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


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

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $role  = $user->roles->first()?->name;

        // ✅ role ke hisaab se factory_id nikalo
        $factoryId = null;

        if ($role === 'manager') {
            $factoryId = Production::where('manager_id', $user->id)
                ->value('factory_id');
        } elseif ($role === 'employee') {
            $factoryId = Employee::where('user_id', $user->id)
                ->value('factory_id');
        }

        \Log::info([
            'user_id'    => $user->id,
            'role'       => $role,
            'factory_id' => $factoryId
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'user'  => [
                    'id'         => $user->id,
                    'name'       => $user->name,
                    'email'      => $user->email,
                    'phone_no'   => $user->phone_no,
                    'cnic'       => $user->cnic,
                    'address'    => $user->address,
                    'pic'        => $user->pic,
                    'roles'      => $user->roles,
                    'role'       => $role,
                    'factory_id' => $factoryId,
                ]
            ]
        ], 200);
    }

 public function forgotPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $user = User::where('email', $request->email)->first();

    // Generate a secure token
    $token = Str::random(64);

    // Store token in password_reset_tokens table
    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $user->email],
        [
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now(),
        ]
    );

    // Build the reset link
    $resetUrl = url('/api/reset-password?token=' . $token . '&email=' . urlencode($user->email));

    // Inline HTML email content
    $htmlContent = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Reset Password</title>
    </head>
    <body style="margin:0; padding:0; background-color:#f4f6f9; font-family: Arial, Helvetica, sans-serif;">
        <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
            <tr>
                <td align="center">
                    <table width="480" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.1); overflow:hidden;">
                        <tr>
                            <td style="background-color:#4f46e5; padding:25px; text-align:center;">
                                <h1 style="color:#ffffff; margin:0; font-size:22px;">Password Reset Request</h1>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:30px 35px;">
                                <p style="color:#333333; font-size:15px; line-height:1.6;">
                                    Hi ' . e($user->name) . ',
                                </p>
                                <p style="color:#555555; font-size:14px; line-height:1.6;">
                                    We received a request to reset your password. Click the button below to choose a new password. This link will expire in 60 minutes.
                                </p>
                                <div style="text-align:center; margin:30px 0;">
                                    <a href="' . $resetUrl . '" style="background-color:#4f46e5; color:#ffffff; text-decoration:none; padding:14px 30px; border-radius:6px; font-size:15px; font-weight:bold; display:inline-block;">
                                        Reset Password
                                    </a>
                                </div>
                                <p style="color:#999999; font-size:13px; line-height:1.5;">
                                    If you did not request a password reset, no further action is required. If the button above does not work, copy and paste this link into your browser:
                                </p>
                                <p style="word-break:break-all; font-size:12px; color:#4f46e5;">
                                    ' . $resetUrl . '
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color:#f4f6f9; padding:15px; text-align:center;">
                                <p style="font-size:12px; color:#aaaaaa; margin:0;">
                                    &copy; ' . date('Y') . ' ' . config('app.name') . '. All rights reserved.
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>';

    // Send the mail using inline HTML
    Mail::html($htmlContent, function ($message) use ($user) {
        $message->to($user->email)
                ->subject('Reset Your Password');
    });

    return response()->json([
        'message' => 'Password reset link has been sent to your email.',
    ]);
}
    public function resetPassword(Request $request)
    { 
        return view('reset_password');
    }



public function updatePassword(Request $request)
{
    $request->validate([
        'email'        => ['required', 'email', 'exists:users,email'],
        'token'        => ['required', 'string'],
        'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        // 'confirmed' expects a matching 'new_password_confirmation' field
    ]);

    // Look up the reset record for this email
    $resetRecord = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();

    if (!$resetRecord) {
        throw ValidationException::withMessages([
            'email' => ['No password reset request found for this email.'],
        ]);
    }

    // Check token validity (compare against the hashed token)
    if (!Hash::check($request->token, $resetRecord->token)) {
        throw ValidationException::withMessages([
            'token' => ['This password reset link is invalid.'],
        ]);
    }

    // Check expiry (e.g. 60 minutes)
    $expiresInMinutes = 60;
    if (Carbon::parse($resetRecord->created_at)->addMinutes($expiresInMinutes)->isPast()) {
        throw ValidationException::withMessages([
            'token' => ['This password reset link has expired. Please request a new one.'],
        ]);
    }

    // Fetch the user and update their password
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        throw ValidationException::withMessages([
            'email' => ['User not found.'],
        ]);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    // Invalidate the token so the link can't be reused
    DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->delete();

    return response()->json([
        'message' => 'Password has been reset successfully.'
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

// everything is working fine