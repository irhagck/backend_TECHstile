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
use App\Http\Controllers\Api\ManagerController;
use App\Http\Controllers\Api\MachineAssignmentController;
use App\Http\Controllers\Api\ApproveProductionController;
use App\Http\Controllers\Api\FactoryUsersController;
use App\Http\Controllers\Api\ManagerSettingController;
use App\Http\Controllers\Api\NotificationController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);
Route::get('/employee/profile/{id}', [EmployeeDashController::class, 'profile']);

/*
|--------------------------------------------------------------------------
| FACTORY USERS + EMPLOYEES BY FACTORY
|--------------------------------------------------------------------------
*/

Route::get('/factory-users/{factoryId}', [FactoryUsersController::class, 'getUsersByFactory']);
Route::get('/employees-by-factory/{factoryId}', [FactoryUsersController::class, 'getEmployeesByFactory']);

/*
|--------------------------------------------------------------------------
| MANAGER ROUTES (public — abhi bina auth ke)
|--------------------------------------------------------------------------
*/

Route::prefix('manager')->group(function () {
    Route::get('dashboard/{factoryId}', [ManagerController::class, 'dashboard']);
    Route::get('machines/{factoryId}', [ManagerController::class, 'machines']);
    Route::get('employees/{factoryId}', [ManagerController::class, 'employees']);
});

Route::get('/manager/employee-details/{employeeId}', [ManagerController::class, 'employeeDetails']);

Route::put('/manager/profile/{id}', [ManagerSettingController::class, 'updateProfile']);
Route::post('/manager/change-password', [ManagerSettingController::class, 'changePassword']);
Route::get('/manager/profile/{userId}', [ManagerController::class, 'profile']);

// NOTIFICATION
Route::get('notifications/{user}', [NotificationController::class, 'index']);
Route::post('notifications/read/{id}', [NotificationController::class, 'read']);
Route::post('notifications/create', [NotificationController::class, 'store']);
Route::get(
'/notifications/unread/{userId}',
[NotificationController::class,'unreadCount']
);

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/assign-machines', [MachineAssignmentController::class, 'assignMachines']);

    // ── Manager Productions ────────────────────────────────
    Route::get('/manager/productions/{factoryId}',
        [ApproveProductionController::class, 'managerProductions']);

    Route::post('/manager/productions/{id}/action',
        [ApproveProductionController::class, 'managerAction']);

    // ── Owner Productions ──────────────────────────────────
    Route::get('/owner/productions/{factoryId}',
        [ApproveProductionController::class, 'ownerProductions']);

    Route::post('/owner/productions/{id}/action',
        [ApproveProductionController::class, 'ownerAction']);

    Route::get('/employees/factories',[EmployeeController::class,'factories']);
    Route::get('/employees/users',[EmployeeController::class,'users']);
    Route::get('/employees-with-shift/{factoryId}', [EmployeeController::class, 'employeesWithShiftByFactory']);

    /*
    |---------------------------
    | EMPLOYEE DASHBOARD
    |---------------------------
    */
    Route::get('/employee/dashboard/{id}', [EmployeeDashController::class, 'dashboard']);

    Route::get('/user/profile/{id}',[EmployeeDashController::class,'userProfile']);
 
    /*
    |---------------------------
    | ROLES (OWNER ONLY)
    |---------------------------
    */
    Route::middleware(['role:owner'])->prefix('roles')->group(function () {
        Route::get('/all',          [RoleController::class, 'index']);
        Route::post('/add',         [RoleController::class, 'store']);
        Route::get('/edit/{id}',    [RoleController::class, 'show']);
        Route::put('/update/{id}',  [RoleController::class, 'update']);
        Route::delete('/delete/{id}', [RoleController::class, 'destroy']);
    });

    /*
    |---------------------------
    | PERMISSIONS (OWNER ONLY)
    |---------------------------
    */
    Route::middleware(['role:owner'])->group(function () {
        Route::get('permissions/all',          [PermissionController::class, 'getAllPermissions']);
        Route::get('role-permissions/{id}',    [PermissionController::class, 'getRolePermissions']);
        Route::post('permissions/sync',        [PermissionController::class, 'syncPermissions']);
    });

    /*
    |---------------------------
    | FACTORY
    |---------------------------
    */
    Route::middleware(['permission:view factories'])->prefix('factories')->group(function () {
        Route::get('allfactories',        [FactoryController::class, 'index']);
        Route::get('dashboard/{id}', [FactoryController::class, 'dashboard']);
        Route::get('productions/factory/{factoryId}', [ProductionController::class, 'byFactory']);
        Route::post('addfactory',         [FactoryController::class, 'store']);
        Route::get('editfactory/{id}',    [FactoryController::class, 'show']);
        Route::put('updatefactory/{id}',  [FactoryController::class, 'update']);
        Route::delete('deletefactory/{id}', [FactoryController::class, 'destroy']);
        Route::get('dashboard/{id}',[FactoryController::class,'dashboard']);
});

    /*
    |---------------------------
    | USERS (OWNER ONLY)
    |---------------------------
    */
    Route::middleware(['role:owner'])->prefix('users')->group(function () {

        Route::get('all',           [UserController::class, 'index']);
        Route::get('managers', [UserController::class, 'managers']);
        Route::get('employees', [UserController::class, 'employees']);
        Route::post('add',          [UserController::class, 'store']);
        Route::get('edit/{id}',     [UserController::class, 'edit']);
        Route::put('update/{id}',   [UserController::class, 'update']);
        Route::delete('delete/{id}', [UserController::class, 'destroy']);
    });

    /*
    |---------------------------
    | MACHINES
    |---------------------------
    */
    Route::prefix('machines')->group(function () {
        Route::get('/all/{factoryId}',[MachineController::class, 'index'])->middleware('permission:view machines');
        Route::post('/add_machine',     [MachineController::class, 'store'])->middleware('permission:create machines');
        Route::get('edit_machine/{id}', [MachineController::class, 'edit'])->middleware('permission:edit machines');
        Route::put('update_machine/{id}', [MachineController::class, 'update'])->middleware('permission:edit machines');
        Route::delete('delete_machine/{id}', [MachineController::class, 'destroy'])->middleware('permission:delete machines');
        Route::get('/details/{id}',     [MachineController::class, 'details'])->middleware('permission:view machines');
    });

    /*
    |---------------------------
    | PRODUCTIONS
    |---------------------------
    */
    Route::prefix('productions')->group(function () {
        Route::get('/all_production',          [ProductionController::class, 'index'])->middleware('permission:view productions');
        Route::post('/add_production',         [ProductionController::class, 'store'])->middleware('permission:create productions');
        Route::get('/edit_production/{id}',    [ProductionController::class, 'edit'])->middleware('permission:edit productions');
        Route::put('/update_production/{id}',  [ProductionController::class, 'update'])->middleware('permission:edit productions');
        Route::delete('/delete_production/{id}', [ProductionController::class, 'destroy'])->middleware('permission:delete productions');
    });

    /*
    |---------------------------
    | EMPLOYEES
    |---------------------------
    */
    Route::prefix('employees')->group(function () {
         Route::get('/employees/factory/{factoryId}',[EmployeeController::class, 'byFactory']);
        Route::get('/all_employee',          [EmployeeController::class, 'index'])->middleware('permission:view employees');
        Route::post('/add_employee',         [EmployeeController::class, 'store']);
        //->middleware('permission:create employees');
        Route::get('/edit_employee/{id}',    [EmployeeController::class, 'edit'])->middleware('permission:edit employees');
        Route::put('/update_employee/{id}',  [EmployeeController::class, 'update'])->middleware('permission:edit employees');
        Route::delete('/delete_employee/{id}', [EmployeeController::class, 'destroy'])->middleware('permission:delete employees');
    });

    /*
    |---------------------------
    | ATTENDANCE
    |---------------------------
    */
    Route::prefix('attendence')->group(function () {
        Route::get('/all_attendence',          [AttendenceController::class, 'index'])->middleware('permission:view attendance');
        Route::post('/add_attendence',         [AttendenceController::class, 'store'])->middleware('permission:create attendance');
        Route::get('/edit_attendence/{id}',    [AttendenceController::class, 'edit'])->middleware('permission:edit attendance');
        Route::put('/update_attendence/{id}',  [AttendenceController::class, 'update'])->middleware('permission:edit attendance');
        Route::delete('/delete_attendence/{id}', [AttendenceController::class, 'destroy'])->middleware('permission:delete attendance');
        Route::post('/mark_attendance', [AttendenceController::class, 'markAttendance'])->middleware('permission:mark attendance');
        });

    // Employee Machine Details Route
   // Machine details — scan ke baad
Route::get('/employee/machine-details/{id}', [EmployeeDashController::class, 'machineDetails']) ->middleware('permission:view machines');

Route::prefix('productions')->group(function () {
// Production enter karna
Route::post('/productions/add_production',    [ProductionController::class, 'store'])->middleware('permission:create productions');


  Route::get('/productions/pending',[ProductionController::class,'pending'])->middleware('permission:verify production');


  Route::get('/productions/pending',[ProductionController::class,'pending'])->middleware('permission:approve production');


   Route::get('/productions/approved',[ProductionController::class,'approve'])->middleware('permission:view productions');

   Route::get('/productions/rejected',[ProductionController::class,'reject'])->middleware('permission:view productions');

    Route::get('/employee/dashboard/{id}', [EmployeeDashController::class, 'dashboard']);

     Route::get('/employee/profile/{id}',[EmployeeDashController::class, 'profile'])->middleware('permission:view profile');

    // Attendance mark karna
    Route::post('/attendence/mark_attendance', [AttendenceController::class, 'markAttendance'])->middleware('permission:mark attendance');

     // Attendance details
     Route::get('/attendence/employee_attendance/{employee_id}', [AttendenceController::class, 'employeeAttendance'])->middleware('permission:view attendance');

     // Employee Dashboard

Route::put(
'/productions/approve/{id}',
[ProductionController::class,'approve']
);

Route::get('/employee/dashboard/{id}', [EmployeeDashController::class, 'dashboard']);

});


Route::put(
'/productions/reject/{id}',[ProductionController::class,'reject']);
// Route::get('/employee/profile/{id}',[EmployeeDashController::class, 'profile'])->middleware('permission:view profile');
//assign production to employee using unique batch_id
Route::post('/assign-production', [ProductionController::class, 'assignProduction'])->middleware('permission:create productions');
//employee history route
Route::get('/employee/history/{id}',[EmployeeDashController::class,'employeeHistory'])->middleware('permission:view productions');

}); // auth:sanctum group end
