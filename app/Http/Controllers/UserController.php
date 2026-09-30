<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Production;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;

class UserController extends Controller
{
    /* ------------------------------------------------------------------
     |  Helpers
     |------------------------------------------------------------------ */

    /**
     * Email / phone / cnic ko ek hi format mein la do taake
     * "0300-1234567" aur "03001234567" alag record na banein.
     */
    private function normalizeInput(Request $request): void
    {
        $data = [];

        if ($request->has('email')) {
            $data['email'] = strtolower(trim((string) $request->email));
        }

        // Phone -> 03XX-XXXXXXX
        if ($request->has('phone_no')) {
            $digits = preg_replace('/\D/', '', (string) $request->phone_no);
            if (str_starts_with($digits, '92') && strlen($digits) === 12) {
                $digits = '0' . substr($digits, 2);
            }
            $data['phone_no'] = strlen($digits) === 11
                ? substr($digits, 0, 4) . '-' . substr($digits, 4)
                : $digits;
        }

        // CNIC -> XXXXX-XXXXXXX-X
        if ($request->has('cnic')) {
            $digits = preg_replace('/\D/', '', (string) $request->cnic);
            $data['cnic'] = strlen($digits) === 13
                ? substr($digits, 0, 5) . '-' . substr($digits, 5, 7) . '-' . substr($digits, 12, 1)
                : $digits;
        }

        $request->merge($data);
    }

    /**
     * 0000000, 1111111, 1234567, 7654321 jaise fake patterns pakadta hai.
     */
    private function isFakeDigits(string $s): bool
    {
        if (strlen($s) < 4) {
            return false;
        }
        if (preg_match('/^(\d)\1+$/', $s)) {
            return true; // sab digits same
        }
        return str_contains('012345678901234567890', $s)
            || str_contains('987654321098765432109', $s); // sequential
    }

    private function phoneRules(): array
    {
        return [
            'required',
            'string',
            // 0300-0349 (Jazz, Zong, Ufone, Telenor) aur 0355 (SCOM)
            'regex:/^(03[0-4]\d|0355)-\d{7}$/',
            function ($attribute, $value, $fail) {
                $digits = preg_replace('/\D/', '', $value);
                if ($this->isFakeDigits(substr($digits, 4))) {
                    $fail('Ye phone number asli nahi lag raha, sahi number likhein');
                }
            },
        ];
    }

    private function cnicRules(): array
    {
        return [
            'required',
            'string',
            // Pehla digit province code (1-7), format 34101-1234567-1
            'regex:/^[1-7]\d{4}-\d{7}-\d$/',
            function ($attribute, $value, $fail) {
                $digits = preg_replace('/\D/', '', $value);
                if (
                    $this->isFakeDigits($digits) ||
                    $this->isFakeDigits(substr($digits, 5, 7))
                ) {
                    $fail('Ye CNIC asli nahi lag raha, sahi CNIC likhein');
                }
            },
        ];
    }

    private function validationMessages(): array
    {
        return [
            'email.unique'    => 'Ye email pehle se registered hai',
            'phone_no.unique' => 'Ye phone number pehle se registered hai',
            'cnic.unique'     => 'Ye CNIC pehle se registered hai',
            'phone_no.regex'  => 'Phone number ka format 03XX-XXXXXXX hona chahiye (valid network code ke sath)',
            'role.in'         => 'Sirf manager ya employee role allowed hai',
            'cnic.regex'      => 'CNIC ka format 34101-1234567-1 hona chahiye',
        ];
    }

    /* ------------------------------------------------------------------
     |  Lists
     |------------------------------------------------------------------ */

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
        $employeeUserIds = Employee::pluck('user_id')->unique();

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

    /* ------------------------------------------------------------------
     |  Uniqueness pre-check (Flutter form isay save se pehle call karta hai)
     |------------------------------------------------------------------ */
    public function checkUnique(Request $request)
    {
        $this->normalizeInput($request);
        $excludeId = $request->input('exclude_id');

        $taken = function (string $column, $value) use ($excludeId) {
            if ($value === null || $value === '') {
                return false;
            }
            return User::where($column, $value)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists();
        };

        return response()->json([
            'success'    => true,
            'emailTaken' => $taken('email', $request->email),
            'phoneTaken' => $taken('phone_no', $request->phone_no),
            'cnicTaken'  => $taken('cnic', $request->cnic),
        ], 200);
    }

    /* ------------------------------------------------------------------
     |  Create
     |------------------------------------------------------------------ */
    public function store(Request $request)
    {
        $this->normalizeInput($request);

        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'string', PasswordRule::defaults()],
            'phone_no' => array_merge($this->phoneRules(), ['unique:users,phone_no']),
            'cnic'     => array_merge($this->cnicRules(), ['unique:users,cnic']),
            'address'  => 'nullable|string',
            'role'     => 'required|string|in:manager,employee|exists:roles,name',
        ], $this->validationMessages());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $user = DB::transaction(function () use ($request) {
                // 1. Create User
                $newUser = User::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                    'phone_no' => $request->phone_no,
                    'cnic'     => $request->cnic,
                    'address'  => $request->address,
                ]);

                // 2. Assign Spatie Role
                $newUser->assignRole($request->role);

                return $newUser;
            });

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data'    => $user->load('roles')
            ], 201);

        } catch (\Illuminate\Database\QueryException $e) {
            // Race condition: DB UNIQUE constraint ne pakad liya
            if ($e->getCode() === '23000') {
                return response()->json([
                    'success' => false,
                    'message' => 'Email, phone number ya CNIC pehle se registered hai',
                ], 409);
            }
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRoles()
    {
        $roles = Role::all();

        return response()->json([
            'success' => true,
            'roles'   => $roles
        ], 200);
    }

    // Get Single User (Edit)
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

    /* ------------------------------------------------------------------
     |  Update
     |------------------------------------------------------------------ */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $this->normalizeInput($request);

        $validator = Validator::make($request->all(), [
            'name'     => 'sometimes|string|max:255',
            'email'    => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['sometimes', 'nullable', PasswordRule::defaults()],
            'phone_no' => array_merge(['sometimes'], $this->phoneRules(), [Rule::unique('users', 'phone_no')->ignore($user->id)]),
            'cnic'     => array_merge(['sometimes'], $this->cnicRules(), [Rule::unique('users', 'cnic')->ignore($user->id)]),
            'address'  => 'nullable|string',
            'role'     => 'sometimes|string|in:manager,employee|exists:roles,name',
        ], $this->validationMessages());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $user->name     = $request->name     ?? $user->name;
            $user->email    = $request->email    ?? $user->email;
            $user->phone_no = $request->phone_no ?? $user->phone_no;
            $user->cnic     = $request->cnic     ?? $user->cnic;
            $user->address  = $request->address  ?? $user->address;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            DB::transaction(function () use ($user, $request) {
                $user->save();

                // Assign role from Spatie
                if ($request->filled('role')) {
                    $user->syncRoles([$request->role]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data'    => $user->load('roles')
            ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json([
                    'success' => false,
                    'message' => 'Email, phone number ya CNIC pehle se registered hai',
                ], 409);
            }
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
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

        $employee = DB::table('employees')->where('user_id', $user->id)->first();

        if ($employee) {
            DB::table('productions')->where('employee_id', $employee->id)->delete();
            DB::table('payments')->where('employee_id', $employee->id)->delete();

            DB::table('employees')->where('id', $employee->id)->delete();
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
        $employeeUserIds = Employee::pluck('user_id')->unique();

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