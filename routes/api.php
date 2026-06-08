<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\FactoryController;
use App\Http\Controllers\Api\MachineController;
use App\Http\Controllers\Api\ProductionController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\EmployeeDashController;
use App\Http\Controllers\Api\AttendenceController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

Route::get('/test', function () {
    $user = User::create([
        'name' => 'test',
        'email' => 'test@test.com',
        'password' => Hash::make('test@test.com'),
    ]);

    return response()->json([
        'message' => 'User created',
        'data' => $user
    ], 201);
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (IMPORTANT)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/profile', [AuthController::class, 'profile']);

    /*
    |---------------------------
    | ROLES (OWNER ONLY)
    |---------------------------
    */
    Route::middleware(['role:owner'])->prefix('roles')->group(function () {
        Route::get('/all', [RoleController::class, 'index']);
        Route::post('/add', [RoleController::class, 'store']);
        Route::get('/edit/{id}', [RoleController::class, 'show']);
        Route::put('/update/{id}', [RoleController::class, 'update']);
        Route::delete('/delete/{id}', [RoleController::class, 'destroy']);
    });

    /*
    |---------------------------
    | PERMISSIONS (OWNER ONLY)
    |---------------------------
    */
    Route::middleware(['role:owner'])->group(function () {
        Route::get('permissions/all', [PermissionController::class, 'getAllPermissions']);
        Route::get('role-permissions/{id}', [PermissionController::class, 'getRolePermissions']);
        Route::post('permissions/sync', [PermissionController::class, 'syncPermissions']);
    });

    /*
    |---------------------------
    | FACTORY (permission based)
    |---------------------------
    */
    Route::middleware(['permission:view factories'])->prefix('factories')->group(function () {
        Route::get('allfactories', [FactoryController::class, 'index']);
        Route::post('addfactory', [FactoryController::class, 'store']);
        Route::get('editfactory/{id}', [FactoryController::class, 'show']);
        Route::put('updatefactory/{id}', [FactoryController::class, 'update']);
        Route::delete('deletefactory/{id}', [FactoryController::class, 'destroy']);
    });

    /*
    |---------------------------
    | USERS (OWNER ONLY)
    |---------------------------
    */
    Route::middleware(['role:owner'])->prefix('users')->group(function () {
        Route::get('all', [UserController::class, 'index']);
        Route::post('add', [UserController::class, 'store']);
        Route::get('edit/{id}', [UserController::class, 'edit']);
        Route::put('update/{id}', [UserController::class, 'update']);
        Route::delete('delete/{id}', [UserController::class, 'destroy']);
    });

    /*
    |---------------------------
    | MACHINES (permission based)
    |---------------------------
    */
    Route::prefix('machines')->group(function () {
        Route::get('/all', [MachineController::class, 'index'])->middleware('permission:view machines');
        Route::post('/add_machine', [MachineController::class, 'store'])->middleware('permission:create machines');
        Route::get('edit_machine/{id}', [MachineController::class, 'edit'])->middleware('permission:edit machines');
        Route::put('update_machine/{id}', [MachineController::class, 'update'])->middleware('permission:edit machines');
        Route::delete('delete_machine/{id}', [MachineController::class, 'destroy'])->middleware('permission:delete machines');
        Route::get('/details/{id}', [MachineController::class, 'details'])->middleware('permission:view machines');
    });

    /*
    |---------------------------
    | PRODUCTIONS
    |---------------------------
    */
    Route::prefix('productions')->group(function () {
        Route::get('/all_production', [ProductionController::class, 'index'])->middleware('permission:view productions');
        Route::post('/add_production', [ProductionController::class, 'store'])->middleware('permission:create productions');
        Route::get('/edit_production/{id}', [ProductionController::class, 'edit'])->middleware('permission:edit productions');
        Route::put('/update_production/{id}', [ProductionController::class, 'update'])->middleware('permission:edit productions');
        Route::delete('/delete_production/{id}', [ProductionController::class, 'destroy'])->middleware('permission:delete productions');
    });

    /*
    |---------------------------
    | EMPLOYEES
    |---------------------------
    */
    Route::prefix('employees')->group(function () {
        Route::get('/all_employee', [EmployeeController::class, 'index'])->middleware('permission:view employees');
        Route::post('/add_employee', [EmployeeController::class, 'store'])->middleware('permission:create employees');
        Route::get('/edit_employee/{id}', [EmployeeController::class, 'edit'])->middleware('permission:edit employees');
        Route::put('/update_employee/{id}', [EmployeeController::class, 'update'])->middleware('permission:edit employees');
        Route::delete('/delete_employee/{id}', [EmployeeController::class, 'destroy'])->middleware('permission:delete employees');
    });

    /*
    |---------------------------
    | ATTENDENCE
    |---------------------------
    */
    Route::prefix('attendence')->group(function () {
        Route::get('/all_attendence', [AttendenceController::class, 'index'])->middleware('permission:view attendance');
        Route::post('/add_attendence', [AttendenceController::class, 'store'])->middleware('permission:create attendance');
        Route::get('/edit_attendence/{id}', [AttendenceController::class, 'edit'])->middleware('permission:edit attendance');
        Route::put('/update_attendence/{id}', [AttendenceController::class, 'update'])->middleware('permission:edit attendance');
        Route::delete('/delete_attendence/{id}', [AttendenceController::class, 'destroy'])->middleware('permission:delete attendance');
    });

Route::prefix('productions')->group(function () {

    Route::get('/all_production', [ProductionController::class, 'index']);        
    Route::post('/add_production', [ProductionController::class, 'store']);       
    Route::get('/edit_production/{id}', [ProductionController::class, 'edit']);   
    Route::put('/update_production/{id}', [ProductionController::class, 'update']);   
    Route::delete('/delete_production/{id}', [ProductionController::class, 'destroy']); 
});

Route::prefix('employees')->group(function () {

    
    Route::get('/all_employee', [EmployeeController::class, 'index']);      
    Route::post('/add_employee', [EmployeeController::class, 'store']);     
    Route::get('/edit_employee/{id}', [EmployeeController::class, 'edit']); 
    Route::put('/update_employee/{id}', [EmployeeController::class, 'update']); 
    Route::delete('/delete_employee/{id}', [EmployeeController::class, 'destroy']); 

});

Route::prefix('attendence')->group(function () {

    Route::get('/all_attendence', [AttendenceController::class, 'index']);        
    Route::post('/add_attendence', [AttendenceController::class, 'store']);       
    Route::get('/edit_attendence/{id}', [AttendenceController::class, 'edit']);   
    Route::put('/update_attendence/{id}', [AttendenceController::class, 'update']);   
    Route::delete('/delete_attendence/{id}', [AttendenceController::class, 'destroy']); 

});
// Machine details route
Route::get(
    '/machines/details/{id}',
    [MachineController::class, 'details']
);
Route::get('/employee/dashboard/{id}', [EmployeeDashController::class, 'dashboard']);

});


